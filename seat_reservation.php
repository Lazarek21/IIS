<!DOCTYPE html>

<?php if(!isset($_GET['linkPK'])){header("Location:index.php");die();}?>
<html lang="cz">
  <head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link rel="stylesheet" href="seat.css" />
    
    <title>Výběr míst</title>
  </head>
  <body>

  <?php 
  require_once('common.php');
  if(isset($_SESSION['tmp_login']))
    unset($_SESSION['tmp_login']);
  
  ?>
  <div style="position:absolute;left:50%;top:10%;transform: translate(-50%, 0%);">
    <div class="seatsSelect">

      <div class="container">
        <div class="lightRow">
          <div class="light"></div>
          <div class="light"></div>
        </div>

        <div class="bus">
          <?php
            require_once('parse_table.php');
            update_state_seat($_GET['linkPK']);
          ?>
        </div>
      </div>

        
      </div>
      <div class="seatsSelect">
      <ul class="showcase">
          <li>
            <div class="seat selected unclickable"></div>
            <small>Vybrané</small>
          </li>
          <li>
            <div class="seat occupied"></div>
            <small>Rezervované</small>
          </li>
        </ul>

        <ul class="priceShowcase">
          <li>
            <div class="seat unclickable pc1"></div>
            <small>VIP kategorie</small>
          </li>
          <li>
            <div class="seat unclickable pc2"></div>
            <small>1. třída</small>
          </li>
          <li>
            <div class="seat unclickable pc3"></div>
            <small>2. třída</small>
          </li>
        </ul>

        <p class="text">
          Počet vybraných míst: <span id="count">0</span><br>
        </p>
        <p class="text">
          Cena: <span id="price">0</span> Kč<br>
        </p>
        <p class="text">
          <?php
            echo '<form id="form" action="javascript:void(0);">';  
            if(!isset($_SESSION['login'])){
              echo '<div class="horLayout">';
              echo '<label for="email">E-mail</label>';
              echo '</div>';
              echo '<div class="horLayout">';
              echo '<input type="text" name="email" class="newInput" id="email"></input>';
              echo '<div class="horLayout">';
              
            } else {
              $_GET['email'] = $_SESSION['login'];
            }
            echo '<input type="submit" class="reserveButton" value="Rezervovat"></input>';
            echo '</form><br>';
            echo '<p style="font-size:20px;color:red">';
            if (isset($_SESSION['reservationErr'])){
              echo $_SESSION['reservationErr'];
              unset($_SESSION['reservationErr']);
            }
            echo '</p>';
          ?>
        </p>
        
      </div>
    </div>
    <script src="seat.js" type="text/javascript"></script>
  </body>
</html>