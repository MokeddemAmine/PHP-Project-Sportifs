<?php
    try{
        $pdo = new PDO('mysql:host=localhost;dbname=sports','root','');
    }catch(PDOException $e){
        $pdo = new PDO('mysql:host=localhost;dbname=mysql','root','');
        $query = $pdo->prepare("CREATE DATABASE Sports");
        $query->execute();
        $pdo = new PDO('mysql:host=localhost;dbname=sports','root','');
    }
    try{
        $query = $pdo->prepare("SELECT name FROM Person LIMIT 1");
        $query->execute();
    
    }catch(PDOException $e){
        if(str_contains($e->getMessage(),'Base table or view not found')){
            $query = $pdo->prepare("CREATE TABLE Person(
                idPerson VARCHAR(20) NOT NULL,
                email VARCHAR(50) NOT NULL,
                name VARCHAR(20),
                fname VARCHAR(20),
                departement VARCHAR(20),
                CONSTRAINT personPK PRIMARY KEY (idPerson),
                CONSTRAINT emailunique UNIQUE (email)
            )");
            $query->execute();
            $query = $pdo->prepare("CREATE TABLE Sport(
                idSport VARCHAR(20) NOT NULL,
                design VARCHAR(20),
                CONSTRAINT sportPK PRIMARY KEY (idSport)
            )");
            $query->execute();
            $query = $pdo->prepare("CREATE TABLE Play(
                idPerson VARCHAR(20) NOT NULL,
                idSport VARCHAR(20) NOT NULL,
                level VARCHAR(15),
                CONSTRAINT playPK PRIMARY KEY (idPerson,idSport),
                CONSTRAINT idpersonFK FOREIGN KEY (idPerson) REFERENCES Person (idPerson),
                CONSTRAINT idsportFK FOREIGN KEY (idSport) REFERENCES Sport (idSport)
        
            )");
            $query->execute();
        }
    }
    function id($id){
        $caracters = "0123456789ABCDEFJHIJKLMNOPQRSTUVWXYZ";
        $count = strlen($caracters);
        $random='';
        for($i=0;$i<$id;$i++){
            $random.=$caracters[rand(0,$count-1)];
        }
        return $random;
    }  
    function idPerson(){
        return id(20);
    }
    function idSport(){
        return id(6);
    }
?>