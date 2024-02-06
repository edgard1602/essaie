<?php

class Database {
    private $host;
    private $database;
    private $username;
    private $password;
    private $conn;

    public function __construct($host, $database, $username, $password) {
        $this->host = $host;
        $this->database = $database;
        $this->username = $username;
        $this->password = $password;
    }

    public function connect() {
        $this->conn = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->conn->connect_error) {
            die("Erreur de connexion à la base de données : " . $this->conn->connect_error);
        }
    }

    public function close() {
        $this->conn->close();
    }

    public function getConnection() {
        return $this->conn;
    }
}

class Faculte {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    public function insertFaculte($code, $libelle) {
        $conn = $this->db->getConnection();

        // Préparer et exécuter la requête d'insertion
        $query = "INSERT INTO faculte (code, libelle) VALUES (?, ?)";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("ss", $code, $libelle);

        if ($stmt->execute()) {
            echo "Enregistrement réussi.";
        } else {
            echo "Erreur lors de l'enregistrement : " . $conn->error;
        }

        $stmt->close();
    }
}

// Utilisation des classes pour enregistrer dans la table faculte
$host = "localhost";
$database = "universite";
$username = "admin";
$password = "password";

// Créer une instance de la classe Database
$db = new Database($host, $database, $username, $password);
$db->connect();

// Créer une instance de la classe Faculte
$faculte = new Faculte($db);

// Récupérer les données du formulaire (supposons que vous avez reçu ces données par POST)
$code = $_POST['code']; // Assurez-vous de valider et nettoyer ces données avant l'utilisation
$libelle = $_POST['libelle'];

// Appeler la méthode pour insérer dans la table faculte
$faculte->insertFaculte($code, $libelle);

// Fermer la connexion à la base de données
$db->close();

?>