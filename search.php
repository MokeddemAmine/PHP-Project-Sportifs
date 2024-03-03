<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="imgs/favicon.jpeg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>Search</title>
</head>
<body>
    <?php include "connect.php" ?>
    <?php include "navbar.php" ?>
    <section class="search">
        <form method="POST">
            <table border="0">
                <tr>
                    <td><label for="sport">sport</label></td>
                    <td><select name="sport" id="sport" class="input">
                        <option hidden>SELECT SPORT</option>
                        <?php
                            $query = $pdo->prepare("SELECT design FROM Sport");
                            $query->execute();
                            while($sport=$query->fetchObject()){
                                print('<option value="'.$sport->design.'">'.$sport->design.'</option>');
                            }
                        ?>
                        
                    </select></td>
                </tr>
                <tr>
                    <td><label for="level">level</label></td>
                    <td><select name="level" id="level" class="input">
                        <option hidden>SELECT LEVEL</option>
                        <option value="amateur">Amateur</option>
                        <option value="semi medium">Semi-Medium</option>
                        <option value="medium">Medium</option>
                        <option value="upper">Upper</option>
                        <option value="profession">Profession</option>
                        <option value="super">Super</option>
                    </select></td>
                </tr>
                <tr>
                    <td><label for="department">department</label></td>
                    <td><select name="department" id="department" class="input">
                        <option hidden>SELECT DEPARTMENT</option>
                        <?php
                            $query = $pdo->prepare("SELECT DISTINCT departement FROM Person");
                            $query->execute();
                            while($depart = $query->fetchObject()){
                                print('<option value="'.$depart->departement.'">'.$depart->departement.'</option>');
                            }
                        ?>
                        
                    </select></td>
                </tr>
                <tr>
                    <td colspan="2" style="text-align: center;"><input type="submit" value="Search" name="search"></td>
                </tr>
            </table>
        </form>
        <?php
            if(isset($_POST['search'])){
                if($_POST['sport'] == 'SELECT SPORT' && $_POST['level'] == 'SELECT LEVEL' && $_POST['department'] == 'SELECT DEPARTMENT'){
                    print('<script>alert("select un order")</script>');
                }
                else{
                    $search = "";
                    $array_search = array();
                    if($_POST['sport'] != 'SELECT SPORT'){
                        $search .="AND Play.idSport IN (SELECT idSport FROM Sport WHERE design = :design)";
                        $array_search = [':design'=>$_POST['sport']];
                    }
                    if($_POST['level'] != 'SELECT LEVEL'){
                        $search .="AND level=:level";
                        $array_search = [...$array_search,':level'=>$_POST['level']];
                    }
                    if($_POST['department'] != 'SELECT DEPARTMENT'){
                        $search .="AND departement=:department";
                        $array_search = [...$array_search,':department'=>$_POST['department']];
                    }
                    $query = $pdo->prepare("SELECT email,name,fname, departement,level,design  FROM Person  INNER JOIN Play ON Person.idPerson = Play.idPerson INNER JOIN Sport ON Play.idSport = Sport.idSport WHERE Person.idPerson != :idPerson ".$search);
                    $array_search = [...$array_search,':idPerson'=>$_COOKIE['user']['idPerson']];
                    $query->execute($array_search);
                    if($query->rowCount() > 0){
                        ?>
                        <div class="table-result">
                            <table border="1">
                                <thead>
                                    <tr>
                                    <th>name</th>
                                    <th>family name</th>
                                    <th>email</th>
                                    <th>department</th>
                                    <th>sport</th>
                                    <th>level</th>
                                    </tr>
                                </thead>
                                <tbody>
                        <?php
                        while($person = $query->fetchObject()){
                            print('<tr>');
                                print('<td>'.$person->name.'</td>');
                                print('<td>'.$person->fname.'</td>');
                                print('<td>'.$person->email.'</td>');
                                print('<td>'.$person->departement.'</td>');
                                print('<td>'.$person->design.'</td>');
                                print('<td>'.$person->level.'</td>');
                            print('</tr>');
                        }
                        ?>
                                </tbody>
                            </table>
                        </div>
                        <?php
                    }
                    
                }
            }
        ?>
    </section>
</body>
</html>