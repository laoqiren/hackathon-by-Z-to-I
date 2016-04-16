<?php 
    //header('location:index.html');
for($i = 1;$i < 1000;$i++){
    echo 'http://192.168.177.130/hackathon/api.php?action=ticket&eventId=1&userId='.$i,"\n";
}
