<td><a href = 'crud.php?show=<?php echo $v['id']; ?>'><img src="images/show.png"></a></td>
<?php if (isset($_SESSION['identificado'])){
        echo "<td><a href = 'crud.php?edit="; echo $v['id'];"'><img src='images/edit.png'></a></td>";
        echo "<td><a href = 'crud.php?del="; echo $v['id'];"'><img src='images/show.png'></a></td>";
      }
?>
