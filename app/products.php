<?php
	$title = 'Car Search Results';
	$description = 'Lorem Ipsum ...';
	include 'inc/header.php';
	include "db_operations.php";
?>
<!--GET HEADER-->

<!--MAIN START-->
<main>
  <section>
    <span id="expand" class="right">Refine search</span>
    <div id="expanded">
      <form action="products.php" class="refine" method="post">
              <script type="text/javascript" src="jQueryJavaScriptLibrary.js"></script>
              <script type="text/javascript">
                  $(document).ready(function(){
                      $(".carMake").change(function() {
                          var id=$(this).val();
                          var dataString = 'id='+ id;

                          $.ajax({
                              type: "GET",
                              url: "carModel.php",
                              data: dataString,
                              cache: false,
                              success: function(html)
                              {
                                  $(".carModel").html(html);
                              }
                          });
                      });
                  });
              </script>
              <select name="make" id="model" class="carMake">
                  <option selected="selected">--Select Make--</option>
                  <?php
                      include('db.php');
                      $sql=mysql_query("SELECT distinct manufacturer FROM UsedCars");
                      while($row=mysql_fetch_array($sql))
                      {
                          $id=$row['manufacturer'];
                          echo '<option value="'.$id.'">'.$id.'</option>';
                      }
                  ?>
              </select>
              <select name="model" id="model" class="carModel">
                  <option selected="selected">--Select Model--</option>
              </select>

          <select name="minYear" id="minYear">
                      <option selected="selected">--min year--</option>
                      <?php for($i = 1999; $i < 2016; $i++){ echo "<option>$i</option>";} ?>
                  </select>
          <select name="maxYear" id="maxYear">
                      <option selected="selected">--max year--</option>
                      <?php for($i = 1999; $i < 2016; $i++){ echo "<option>$i</option>";} ?>
                  </select>
          <input type="range" name="maxPrice" id="slider" min="1000" value="50000" max="50000" step="1000">

        <button type="submit">Submit</button>
      </form>
    </div>
  </section>

	<section class="gallery">
      <?php
      // For debugging purposes only
      //echo "make: ".$_POST['make']."</br> model: ".$_POST['model']."</br> minYear: ".$_POST['minYear']."</br> submit: ".$_POST['submit'];

          //if url is access from outside index.php
          if((!isset($_POST['make'])&&!isset($_POST['type']))){
              echo "<h2>All cars in DB</h2><ul>";
              getAllCarsInDB();
          }

          //user click on manufacturer name on product page
          elseif(isset($_POST['submit'])==true){
              echo "<h1> just make no model</h1>";
              getMake($_POST['make']);
          }

          //if user selects the type of car (right side menu on index page)
          elseif(isset($_POST['type'])==true){
              echo "<h2> ".$_POST['type']."</h2><ul>";
              getSpecificType($_POST['type']);
          }

          //If users selects only make and model(model is automatically selected once make is picked)
          elseif(($_POST['make']!=="--Select Make--")&&($_POST['minYear']=="--min year--")&&($_POST['maxYear']=="--max year--")){
              echo "<h2>TEST q for make and model + max price </h2><ul>";
              getMakeModelMaxPrice($_POST['make'],$_POST['model'],$_POST['maxPrice']);
          }

          //If user selects only min year
          elseif(($_POST['make']=="--Select Make--")&&($_POST['minYear']!=="--min year--")&&($_POST['maxYear']=="--max year--")){
              echo "<h2>TEST q for min year + max price </h2><ul>";
              getMinYearMaxPrice($_POST['minYear'],$_POST['maxPrice']);
          }

          //If users selects only max year
          elseif(($_POST['make']=="--Select Make--")&&($_POST['minYear']=="--min year--")&&($_POST['maxYear']!=="--max year--")){
              echo "<h2>TEST q for max year + max price </h2><ul>";
              getMaxYearMaxPrice($_POST['maxYear'],$_POST['maxPrice']);
          }

          //If user selects make and model and min year (but not max year)
          elseif(($_POST['make']!=="--Select Make--")&&($_POST['minYear']!=="--min year--")&&($_POST['maxYear']=="--max year--")){
              echo "<h2>TEST q for make, model, min year + max price </h2><ul>";
              getMakeModelMinYearMaxPrice($_POST['make'],$_POST['model'],$_POST['minYear'],$_POST['maxPrice']);
          }

          //If user selects make and model and max year (but not min year)
          elseif(($_POST['make']!=="--Select Make--")&&($_POST['minYear']=="--min year--")&&($_POST['maxYear']!=="--max year--")){
              echo "<h2>TEST q for make, model, max year + max price </h2><ul>";
              getMakeModelMaxPrice($_POST['make'],$_POST['model'],$_POST['maxPrice']);
          }

          //If user selects min year, max year (but not make and model)
          elseif(($_POST['make']=="--Select Make--")&&($_POST['minYear']!=="--min year--")&&($_POST['maxYear']!=="--max year--")){
              echo "<h2>TEST q for min year,  max year + max price </h2><ul>";
              getMinYearMaxYearMaxPrice($_POST['minYear'],$_POST['maxYear'],$_POST['maxPrice']);
          }

          //If user selects all fields
          elseif(($_POST['make']!=="--Select Make--")&&($_POST['minYear']!=="--min year--")&&($_POST['maxYear']!=="--max year--")){
              echo "<h2>TEST q make and model, min year, max year + max price </h2><ul>";
              getMakeModelMinYearMaxYearMaxPrice($_POST['make'],$_POST['model'],$_POST['minYear'],$_POST['maxPrice'],$_POST['maxPrice']);
          }

          //if user just use the url or clicks the submit button without filling any fields
          else{
              echo "<h2>All cars in DB</h2><ul>";
              getAllCarsInDB();
          }
      ?>
    </ul>
	</section>
</main>
<!--MAIN END-->

<!--GET FOOTER-->
<?php include 'inc/footer.php'; ?>
