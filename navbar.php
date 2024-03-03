<header>
    <h1><a href="index.php">LOGO</a></h1>
    <nav>
        <?php
            if(isset($_COOKIE['user'])){
                print("<form method='POST'>");
                print('<a href="search.php">search</a>');
                print('<a href="add.php">sign sport</a>');
                print('<input type="submit" value="LOG OUT" name="logout"/>');
                print("</form>");
            }
            else{
                print('<a href="add.php">sign up</a>');
            }
            if(isset($_POST['logout'])){
                setcookie('user[idPerson]',null,time()-3600);
                setcookie('user[email]',null,time()-3600);
                setcookie('user[name]',null,time()-3600);
                setcookie('user[fname]',null,time()-3600);
                setcookie('user[department]',null,time()-3600);
                print('<script>document.location="index.php"</script>');
            }
        ?>
        
    </nav>
</header>