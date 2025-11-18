<?php
class Makhateer
{
  // DB Stuff
  private $conn;
  private $table = 'makhateer';

  // Properties
  public $Id;
  public $Name;
  public $MokhtarId;
  public $Phone;
  public $Password;
  public $CId;
  public $DOB;
  public $Address;
  public $RegionCode;
  public $MemberOfBoardDirector;
  public $ActiveElection;

  public $res;
  // Constructor with DB
  public function __construct($db)
  {
    $this->conn = $db;
  }

  // Get categories
  public function read()
  {
    // Create query
    $query = 'SELECT
        id,
        name, cId, 
          mokhtarId, address,
          phone,regionCode,memberOfBoardDirector,activeElection
      FROM
        ' . $this->table . '
      ';

    // Prepare statement
    $stmt = $this->conn->prepare($query);

    // Execute query
    $stmt->execute();

    return $stmt;
  }

  public function read_Profile()
  {
    // Create query
    $query = 'SELECT
    id,
    name,cId , 
    mokhtarId, phone, dob,address,regionCode,memberOfBoardDirector,activeElection
  FROM
    ' . $this->table . '
WHERE CId = ? 
LIMIT 0,1';

    //Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->CId);

    // Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->res = ($row == null ? false : true);



    // set properties
    $this->Id = $row['id'];
    $this->Name = $row['name'];
    $this->Phone = $row['phone'];
    $this->DOB = $row['dob'];
    $this->Address = $row['address'];
    $this->MemberOfBoardDirector = $row['memberOfBoardDirector'];
    $this->RegionCode = $row['regionCode'];
    $this->ActiveElection = $row['activeElection'];
  }
  // Get Single Category
  public function read_single()
  {
    // Create query
    $query = 'SELECT
          id,
          name,cId , 
          mokhtarId, phone
        FROM
          ' . $this->table . '
      WHERE CId = ? and Password  = ?
      LIMIT 0,1';

    //Prepare statement
    $stmt = $this->conn->prepare($query);

    // Bind ID
    $stmt->bindParam(1, $this->CId);
    $HashedPassword =  md5($this->Password);
    $stmt->bindParam(2, $HashedPassword);

    // Execute query
    $stmt->execute();

    $row = $stmt->fetch(PDO::FETCH_ASSOC);

    $this->res = ($row == null ? false : true);

    if ($this->res != null) {
      $_SESSION["loggedin"] = true;
      $_SESSION["MokhtarId"] = $this->Id;
      $_SESSION["CId"] = $this->CId;
    }


    // set properties
    $this->Id = $row['id'];
    $this->Name = $row['name'];
    $this->Phone = $row['phone'];
  }

  // Create Category
  public function create()
  {
    // Create Query
    $query = 'INSERT INTO ' .
      $this->table . '
    SET
      name = :name , CId = :CID , phone = :phone , address = :address ,regionCode = :regionCode
      ,memberOfBoardDirector	 = :memberOfBoardDirector	, activeElection = :activeElection';

    // Prepare Statement
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->name = htmlspecialchars(strip_tags($this->name));

    // Bind data
    $stmt->bindParam(':CID', $this->CId);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':phone', $this->phone);
    $stmt->bindParam(':address', $this->address);

    $stmt->bindParam(':regionCode', $this->regionCode);
    $stmt->bindParam(':memberOfBoardDirector', $this->memberOfBoardDirector);

    $stmt->bindParam(':activeElection', $this->activeElection);

    // Execute query
    if ($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: $s.\n", $stmt->error);

    return false;
  }

  // Update Category
  public function update()
  {
    // Create Query
    $query = 'UPDATE ' .
      $this->table . '
    SET
    name = :name , CId = :CID , phone = :phone , address = :address 
      WHERE
      CId = :CId2';

    // Prepare Statement
    $stmt = $this->conn->prepare($query);

    // Clean data
    $this->name = htmlspecialchars(strip_tags($this->name));
   // $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind data
    $stmt->bindParam(':CID', $this->CId);
    $stmt->bindParam(':name', $this->name);
    $stmt->bindParam(':phone', $this->phone);
    $stmt->bindParam(':address', $this->address);
    $stmt->bindParam(':CId2', $this->CId);
   

    // Execute query
    if ($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: $s.\n", $stmt->error);

    return false;
  }

  // Delete Category
  public function delete()
  {
    // Create query
    $query = 'DELETE FROM ' . $this->table . ' WHERE id = :id';

    // Prepare Statement
    $stmt = $this->conn->prepare($query);

    // clean data
    $this->id = htmlspecialchars(strip_tags($this->id));

    // Bind Data
    $stmt->bindParam(':id', $this->id);

    // Execute query
    if ($stmt->execute()) {
      return true;
    }

    // Print error if something goes wrong
    printf("Error: $s.\n", $stmt->error);

    return false;
  }
}
