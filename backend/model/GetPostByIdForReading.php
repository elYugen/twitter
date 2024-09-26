<?php
require_once('../function.php');

class GetPostByIdForReading {

    private $db;

    public function __construct($connect) {
        $this->db = $connect->getPDO();
        if (!$this->db) {
            error_log("Erreur: connexion à la base de données échouée");
            echo json_encode(['error' => 'Erreur de connexion à la base de données']);
            exit;
        }
    }

    public function GetPost($postId)
    {
        $query = "SELECT publications.id,  publications.author_id,  publications.content,  publications.publishdate,  publications.image_id,  users.username,  users.pictures,  publication_image.image, (SELECT COUNT(*) FROM comments WHERE comments.publication_id = publications.id) AS comment_count FROM publications JOIN users ON publications.author_id = users.id LEFT JOIN publication_image ON publications.image_id = publication_image.id WHERE publications.id = :id";

        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':id', $postId, PDO::PARAM_INT);
        $stmt->execute();
        $post = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($post) {
            // recup les commentaires pour cette publication
            $commentQuery = "SELECT  comments.id, comments.content, comments.created_at, users.id AS user_id, users.username, users.pictures FROM comments JOIN users ON comments.author_id = users.id WHERE comments.publication_id = :post_id ORDER BY comments.created_at DESC";

            $commentStmt = $this->db->prepare($commentQuery);
            $commentStmt->bindParam(':post_id', $postId, PDO::PARAM_INT);
            $commentStmt->execute();

            $comments = $commentStmt->fetchAll(PDO::FETCH_ASSOC);

            // ajoute les commentaires a la publi
            $post['comments'] = $comments;

            echo json_encode($post);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Publication non trouvée']);
        }
    }
}

?>