<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="imgs/favicon.jpeg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>Add Sport</title>
</head>
<body>
    <?php include "connect.php" ?>
    <?php include "navbar.php" ?>
    <section class="add-sport">
        <form method="POST">
          <div class="data1">
            <?php
                if(!isset($_COOKIE['user'])){
            ?>
                <div class="data">
                <h2>Data Person</h2>
                <table border="0">
                    <tr>
                        <td><label for="name">Name </label> </td>
                        <td><input type="text" name="name" id="name" placeholder="Enter your name" class="input"></td>
                    </tr>
                    <tr>
                        <td><label for="fanily_name">Family Name </label></td>
                        <td><input type="text" name="fname" id="family_name" placeholder="Enter your family name" class="input"/></td>
                    </tr>
                    <tr>
                        <td><label for="department">Departement </label></td>
                        <td><input type="text" name="department" id="department" placeholder="Enter the department you belong to it" class="input"/></td>
                    </tr>
                    <tr>
                        <td><label for="email">Email </label></td>
                        <td><input type="email" name="email" id="email" placeholder="Enter your email" class="input"/></td>
                    </tr>
                </table>
            </div>
            
            <?php
                }
            ?>
            <div class="data">
            <h2>Data Sport</h2>
            <table border="0">
                <tr>
                    <td><label for="sport">Sport</label></td>
                    <td><select name="sport" id="sport" class="select">
                        <option hidden>Select your sport</option>
                        <?php
                            if(!isset($_COOKIE['user'])){
                                $query = $pdo->prepare("SELECT design FROM Sport");
                                $query->execute();
                                while($sport=$query->fetchObject()){
                                    print('<option value="'.$sport->design.'">'.$sport->design.'</option>');
                                }
                            }
                            else{
                                $query = $pdo->prepare("SELECT design FROM Sport WHERE idSport NOT IN (SELECT idSport FROM Play WHERE idPerson = :idPerson)");
                                $query->execute(array(
                                    ':idPerson'=>$_COOKIE['user']['idPerson']
                                ));
                                while($sport=$query->fetchObject()){
                                    print('<option value="'.$sport->design.'">'.$sport->design.'</option>');
                                }
                                
                            }
                        ?>
                        
                    </select></td>
                </tr>
                <tr>
                    <td><label for="level">Level</label></td>
                    <td><select name="level" id="level" class="select">
                        <option hidden>Select Level</option>
                        <option value="amateur">Amateur</option>
                        <option value="semi medium">Semi-Medium</option>
                        <option value="medium">Medium</option>
                        <option value="upper">Upper</option>
                        <option value="profession">Profession</option>
                        <option value="super">Super</option>
                    </select></td>
                </tr>
                <tr id="form1effect">
                    <td><input type="submit" value="Add Me" name="Add"/></td>
                    <td><input type="reset" value="Reset" name="Reset"/></td>
                </tr>
            </table>
            </div>
          </div>
          <div class="data2">
            <div class="data">
                <h2>Add new sport categorie</h2>
                <table border="0">
                    <tr>
                        <td><label for="newsport">Sport Name</label></td>
                        <td><input type="text" name="newsport" id="newsport" placeholder="Add New Sport Categorie Here" class="input"/></td>
                    </tr>
                    <tr>
                        <td colspan="2" style="text-align: center;"><input type="submit" value="Add Sport" name="addnewsport"/></td>
                    </tr>
                </table>
            </div>
          </div>
        </form>
    </section>
    <?php
        if(isset($_POST['Add'])){
            if(!isset($_COOKIE['user'])){
                if($_POST['name']=="" || $_POST['fname']=="" || $_POST['department']=="" || $_POST['email']=="" || $_POST['sport']=="Select your sport" || $_POST['level']=="Select Level"){
                    print("<script>alert('you forget some data !!!');</script>");
                }
                else{
                    $query = $pdo->prepare('SELECT * FROM Person WHERE email=:email');
                    $query->execute(array(
                        ':email'=>$_POST['email']
                    ));
                    if($query->rowCount()==1){
                        print("<script>alert('".$_POST['email']." has been exist')</script>");
                    }
                    else{
                        $idPerson = idPerson();
                        $query = $pdo->prepare("INSERT INTO Person VALUES (:idPerson,:email,:name,:fname,:department)");
                        $query->execute(array(
                            ':idPerson'=>$idPerson,
                            ':name'=>$_POST['name'],
                            ':fname'=>$_POST['fname'],
                            ':department'=>$_POST['department'],
                            ':email'=>$_POST['email']
                        ));
                        $query = $pdo->prepare("SELECT idSport FROM Sport WHERE design=:design");
                        $query->execute(array(
                            ':design'=>$_POST['sport']
                        ));
                        $idSport = $query->fetchObject()->idSport;
                        $query = $pdo->prepare("INSERT INTO Play VALUES (:idPerson,:idSport,:level)");
                        $query->execute(array(
                            ':idPerson'=>$idPerson,
                            ':idSport'=>$idSport,
                            ':level'=>$_POST['level']
                        ));
                        print("<script>document.location='index.php';</script");
                    }
                }
            }
            else{
                if($_POST['sport']=="Select your sport" || $_POST['level']=="Select Level"){
                    print("<script>alert('you forget some data !!!');</script>");
                }
                else{
                    $query = $pdo->prepare("SELECT idSport FROM Sport WHERE design=:design");
                    $query->execute(array(
                        ':design'=>$_POST['sport']
                    ));
                    $idSport = $query->fetchObject()->idSport;
                    $query = $pdo->prepare("INSERT INTO Play VALUES (:idPerson,:idSport,:level)");
                    $query->execute(array(
                        ':idPerson'=>$_COOKIE['user']['idPerson'],
                        ':idSport'=>$idSport,
                        ':level'=>$_POST['level']
                    ));
                    print("<script>document.location='index.php';</script");
                }
            }
        }
        if(isset($_POST['addnewsport'])){
            if($_POST['newsport'] == ""){
                print("<script>alert('Enter a sport name');</script>");
            }
            else{
                $design = $_POST['newsport'];
                $query = $pdo->prepare("SELECT design FROM Sport WHERE design=:design");
                $query->execute(array(
                    ':design'=>$design
                ));
                if($query->rowCount() == 0){
                    $idSport = idSport();
                    $query = $pdo->prepare("INSERT INTO Sport VALUES (:idSport,:design)");
                    $query->execute(array(
                        ':idSport'=>$idSport,
                        ':design'=>$design
                    ));
                }
                else{
                    print("<script>alert('Sport ".$design." exist !!!');</script>");
                }
            }
        }
    ?>
    >
</body>
</html>