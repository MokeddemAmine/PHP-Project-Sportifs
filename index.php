<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="imgs/favicon.jpeg" type="image/x-icon">
    <link rel="stylesheet" href="style.css">
    <title>Sport</title>
</head>
<body>
    <?php include "connect.php" ?>
    <?php include "navbar.php" ?>
    <section class="body">
        <aside>
            <h2>Sports list</h2>
            <?php
                $query = $pdo->prepare("SELECT design FROM Sport ORDER BY design");
                $query->execute();
                while($sport = $query->fetchObject()){
                    print("<span>".$sport->design."</span>");
                }
            ?>
        </aside>
        <div class="content">
            <?php
                if(!isset($_COOKIE['user'])){
                    print('<form method="POST">
                            <table border="0">
                                <tr>
                                    <td><input type="email" name="email" id="emailHome" placeholder="Enter your email"/></td>
                                </tr>
                                <tr>
                                    <td colspan="2" style="text-align: center;"><input type="submit" value="Sign In" name="sign_in"></td>
                                </tr>
                            </table>
                        </form>'
                    );
                }
                else{
                    print("<p class='welcome'>Welcome <span class='name'>".$_COOKIE['user']['name']."</span> to your sport departement</p>");
                }
                if(isset($_POST['sign_in'])){
                    if($_POST['email'] == ""){
                        print('<script>alert("Enter the email")</script>');
                    }
                    else{
                        $query = $pdo->prepare("SELECT * FROM Person WHERE email=:email");
                        $query->execute(array(
                            ':email'=>$_POST['email']
                        ));
                        if($query->rowCount()==1){
                            $user = $query->fetchObject();
                            setcookie('user[idPerson]',$user->idPerson,time()+60*60);
                            setcookie('user[email]',$user->email,time()+60*60);
                            setcookie('user[name]',$user->name,time()+60*60);
                            setcookie('user[fname]',$user->fname,time()+60*60);
                            setcookie('user[department]',$user->departement,time()+60*60);
                            print('<script>document.location="index.php"</script>');
                        }
                        else{
                            print('<script>alert("Email does not exist")</script>');
                        }

                    }
                }
            ?>
            
        </div>
    </section>
    <?php
    ?>
</body>
</html>

