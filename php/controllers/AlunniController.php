<?php
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;

class AlunniController
{
  public function index(Request $request, Response $response, $args){
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("SELECT * FROM alunni");
    $results = $result->fetch_all(MYSQLI_ASSOC);

    $response->getBody()->write(json_encode($results));
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }

  public function search(Request $request, Response $response, $args){
    $id = $args['id'];
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("SELECT * FROM alunni WHERE id=$id");
    if($result->num_rows == 1){
      $results = $result->fetch_all(MYSQLI_ASSOC);
  
      $response->getBody()->write(json_encode($results));
      return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }
    else{
      $response->getBody()->write(json_encode(['Error' => "alunno non trovato"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(404);
    }
    
  }

  public function create(Request $request, Response $response, $args){
    $body = $request->getBody()->getContents();
    $parsedBody = json_decode($body, true);
    $name = $parsedBody['name'];
    $surname = $parsedBody['surname'];

    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("INSERT INTO alunni (nome, cognome) VALUES ('$name', '$surname')");

    $response->getBody()->write(json_encode($result));
    return $response->withHeader("Content-type", "application/json")->withStatus(200);
  }

  public function update(Request $request, Response $response, $args){
    $id = $args['id'];
    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("SELECT * FROM alunni WHERE id=$id");
    if($result->num_rows == 1){
      $body = $request->getBody()->getContents();
      $parsedBody = json_decode($body, true);
      $name = $parsedBody['name'];
      $surname = $parsedBody['surname'];

      $result = $mysqli_connection->query("UPDATE alunni SET nome='$name', cognome='$surname' WHERE id=$id");

     if($result){
      $response->getBody()->write(json_encode($result));
      return $response->withHeader("Content-type", "application/json")->withStatus(200);
     }
     else{
      $response->getBody()->write(json_encode(["Error" => "errore nell'aggiornamento dei dati"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(400);
     }
    }
    else{
      $response->getBody()->write(json_encode(["Error" => "alunno non trovato"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(404);
    }
  }

  public function remove(Request $request, Response $response, $args){
    $id = $args['id'];

    $mysqli_connection = new MySQLi('my_mariadb', 'root', 'ciccio', 'scuola');
    $result = $mysqli_connection->query("DELETE FROM alunni WHERE id=$id");
    if($mysqli_connection->affected_rows == 1){
      $response->getBody()->write(json_encode($result));
      return $response->withHeader("Content-type", "application/json")->withStatus(200);
    }
    else{
      $response->getBody()->write(json_encode(["Error" => "alunno non trovato"]));
      return $response->withHeader("Content-type", "application/json")->withStatus(404);
    }
  }
}
