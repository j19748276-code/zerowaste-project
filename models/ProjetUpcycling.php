<?php

class ProjetUpcycling {

    private ?int $id; 
    private string $titre;
    private string $summary;
    private string $materielPrincipal; 
    private int $idAuteur;            
    private int $idCategorie;         
    private string $coverImageUrl;
    private array $steps = [];
    private ?PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->id = null;
    }

    public function getId(): ?int {
        return $this->id;
    }

    public function getTitre(): string {
        return $this->titre;
    }

    public function getSummary(): string {
        return $this->summary;
    }

    public function getMaterielPrincipal(): string {
        return $this->materielPrincipal;
    }

    public function getIdAuteur(): int {
        return $this->idAuteur;
    }

    public function getIdCategorie(): int {
        return $this->idCategorie;
    }

    public function getCoverImageUrl(): string {
        return $this->coverImageUrl;
    }
    
    public function getSteps(): array {
        return $this->steps;
    }

    public function setId(int $id): void {
        $this->id = $id;
    }

    public function setTitre(string $titre): void {
        $this->titre = $titre;
    }

    public function setSummary(string $summary): void {
        $this->summary = $summary;
    }

    public function setMaterielPrincipal(string $materielPrincipal): void {
        $this->materielPrincipal = $materielPrincipal;
    }
    
    public function setIdAuteur(int $idAuteur): void {
        $this->idAuteur = $idAuteur;
    }

    public function setIdCategorie(int $idCategorie): void {
        $this->idCategorie = $idCategorie;
    }
    
    public function setCoverImageUrl(string $coverImageUrl): void {
        $this->coverImageUrl = $coverImageUrl;
    }

    public function setSteps(array $steps): void
    {
        $this->steps = $steps;
    }

    public function hydrate(array $data): void
    {
        $this->id = $data['id'] ?? null;
        $this->titre = $data['titre'] ?? '';
        $this->summary = $data['summary'] ?? '';
        $this->materielPrincipal = $data['materiel_principal'] ?? '';
        $this->idAuteur = $data['id_auteur'] ?? 0;
        $this->idCategorie = $data['id_categorie'] ?? 0;
        $this->coverImageUrl = $data['cover_image_url'] ?? '';
    }

    private function hydrateSteps(array $stepsData): void
    {
        $this->steps = array_map(function($step) {
            return $step['description'];
        }, $stepsData);
    }

    public static function findRecent(PDO $db, int $limit = 3): array
    {
        $stmt = $db->query(
            "SELECT p.*, c.nom AS categorie_nom, u.username AS auteur_nom 
             FROM projets_upcycling p
             JOIN categories c ON p.id_categorie = c.id
             JOIN users u ON p.id_auteur = u.id
             ORDER BY p.date_creation DESC
             LIMIT " . (int)$limit
        );
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findAll(PDO $db, string $searchTerm = ''): array
    {
        $sql = "SELECT 
                    p.*, 
                    c.nom AS categorie_nom, 
                    u.username AS auteur_nom,
                    COUNT(s.id) AS step_count
                FROM projets_upcycling p
                JOIN categories c ON p.id_categorie = c.id
                JOIN users u ON p.id_auteur = u.id
                LEFT JOIN project_steps s ON p.id = s.project_id
                ";
        
        $params = [];
        if (!empty($searchTerm)) {
            $sql .= " WHERE p.titre LIKE ? OR p.summary LIKE ? OR p.materiel_principal LIKE ?";
            $likeTerm = '%' . $searchTerm . '%';
            $params = [$likeTerm, $likeTerm, $likeTerm];
        }
        
        $sql .= " GROUP BY p.id";
        $sql .= " ORDER BY p.date_creation DESC";
        
        $stmt = $db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
    public static function findByUser(PDO $db, int $userId): array
    {
        $sql = "SELECT p.*, c.nom AS categorie_nom 
                FROM projets_upcycling p
                JOIN categories c ON p.id_categorie = c.id
                WHERE p.id_auteur = ?
                ORDER BY p.date_creation DESC";
        $stmt = $db->prepare($sql);
        $stmt->execute([$userId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function findById(PDO $db, int $id): ?array
    {
        $stmtProject = $db->prepare(
            "SELECT p.*, c.nom AS categorie_nom, u.username AS auteur_nom 
             FROM projets_upcycling p
             JOIN categories c ON p.id_categorie = c.id
             JOIN users u ON p.id_auteur = u.id
             WHERE p.id = ?"
        );
        $stmtProject->execute([$id]);
        $project = $stmtProject->fetch(PDO::FETCH_ASSOC);
        
        if (!$project) {
            return null;
        }

        $stmtSteps = $db->prepare("SELECT * FROM project_steps WHERE project_id = ? ORDER BY step_order ASC");
        $stmtSteps->execute([$id]);
        $project['steps'] = $stmtSteps->fetchAll(PDO::FETCH_ASSOC);

        return $project;
    }
    
    public static function findByIdSimple(PDO $db, int $id): ?ProjetUpcycling
    {
        $stmtProject = $db->prepare("SELECT * FROM projets_upcycling WHERE id = ?");
        $stmtProject->execute([$id]);
        $data = $stmtProject->fetch(PDO::FETCH_ASSOC);
        
        if (!$data) {
            return null;
        }
        
        $projet = new ProjetUpcycling($db);
        $projet->hydrate($data);
        
        $stmtSteps = $db->prepare("SELECT * FROM project_steps WHERE project_id = ? ORDER BY step_order ASC");
        $stmtSteps->execute([$id]);
        $stepsData = $stmtSteps->fetchAll(PDO::FETCH_ASSOC);
        $projet->hydrateSteps($stepsData);
        
        return $projet;
    }

    public function save(): bool
    {
        $this->db->beginTransaction();
        try {
            $sqlProject = "INSERT INTO projets_upcycling (titre, summary, materiel_principal, cover_image_url, id_categorie, id_auteur) VALUES (?, ?, ?, ?, ?, ?)";
            $stmtProject = $this->db->prepare($sqlProject);
            $stmtProject->execute([
                $this->titre, 
                $this->summary, 
                $this->materielPrincipal, 
                $this->coverImageUrl, 
                $this->idCategorie, 
                $this->idAuteur
            ]);
            
            $this->id = (int)$this->db->lastInsertId();
            
            $sqlStep = "INSERT INTO project_steps (project_id, step_order, description) VALUES (?, ?, ?)";
            $stmtStep = $this->db->prepare($sqlStep);
            
            foreach ($this->steps as $index => $stepDescription) {
                if (!empty(trim($stepDescription))) {
                    $stmtStep->execute([$this->id, $index + 1, $stepDescription]);
                }
            }

            $this->db->commit();
            return true;
            
        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }
    
    public function update(): bool
    {
        if ($this->id === null) {
            return false;
        }
        
        $this->db->beginTransaction();
        try {
            $sqlProject = "UPDATE projets_upcycling SET 
                        titre = ?, 
                        summary = ?, 
                        materiel_principal = ?, 
                        id_categorie = ?, 
                        cover_image_url = ? 
                    WHERE id = ?";
            
            $stmtProject = $this->db->prepare($sqlProject);
            $stmtProject->execute([
                $this->titre, 
                $this->summary, 
                $this->materielPrincipal, 
                $this->idCategorie, 
                $this->coverImageUrl, 
                $this->id
            ]);
            
            $stmtDeleteSteps = $this->db->prepare("DELETE FROM project_steps WHERE project_id = ?");
            $stmtDeleteSteps->execute([$this->id]);
            
            $sqlStep = "INSERT INTO project_steps (project_id, step_order, description) VALUES (?, ?, ?)";
            $stmtStep = $this->db->prepare($sqlStep);
            
            foreach ($this->steps as $index => $stepDescription) {
                if (!empty(trim($stepDescription))) {
                    $stmtStep->execute([$this->id, $index + 1, $stepDescription]);
                }
            }

            $this->db->commit();
            return true;

        } catch (PDOException $e) {
            $this->db->rollBack();
            return false;
        }
    }

    public function delete(): bool
    {
        if ($this->id === null) {
            return false;
        }
        
        $imagePath = __DIR__ . '/../uploads/' . $this->coverImageUrl;
        
        $stmt = $this->db->prepare("DELETE FROM projets_upcycling WHERE id = ?");
        $success = $stmt->execute([$this->id]);
        
        if ($success && file_exists($imagePath) && is_file($imagePath)) {
            @unlink($imagePath);
        }
        return $success;
    }
}