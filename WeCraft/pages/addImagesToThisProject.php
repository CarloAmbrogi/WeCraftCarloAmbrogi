<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Add images to this project (if you are the designer of this project) by the id of the project
  //Only for not confirmed projects which will become unclaimed
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for adding images to this project
    $insertedProjectId = $_POST['insertedProjectId'];
    upperPartOfThePage(translate("Edit project"),"./project.php?id=".urlencode($insertedProjectId));
    $csrftoken = filter_input(INPUT_POST, 'csrftoken', FILTER_SANITIZE_STRING);
    //Check on the input form data
    if (!$csrftoken || $csrftoken !== $_SESSION['csrftoken']){
      addParagraph(translate("Error of the csrf token"));
    } else {
      //Check that this project exists, the user is who has created the project, the project is not confirmed
      if(doesThisProjectExists($insertedProjectId)){
        $projectInfos = obtainProjectInfos($insertedProjectId);
        if($_SESSION["userId"] == $projectInfos["designer"]){
          $thisProjectIsConfirmed = false;
          if($projectInfos["confirmedByTheCustomer"] == 1){
            $thisProjectIsConfirmed = true;
          }
          if(!$thisProjectIsConfirmed){
            //Now checks on the file before adding an image to this project
            if(isset($_FILES['insertedImage']) && $_FILES['insertedImage']['error'] == 0){
              //You have chosen to send a new image to add
              $fileName = $_FILES["insertedImage"]["name"];
              $fileType = $_FILES["insertedImage"]["type"];
              $fileSize = $_FILES['insertedImage']['size'];
              $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
              if($fileSize > maxSizeForAFile){
                addParagraph(translate("The file is too big"));
              } else if(!array_key_exists($fileExtension, permittedExtensions)){
                addParagraph(translate("The extension of the file is not an image"));
              } else if(!in_array($fileType, permittedExtensions)){
                addParagraph(translate("The file is not an image"));
              } else {
                //add the new image with the new file image
                $imgData = file_get_contents($_FILES['insertedImage']['tmp_name']);
                addImageToAProject($insertedProjectId,$fileExtension,$imgData);
                //make the project unclaimed
                makeThisProjectUnclaimed($insertedProjectId);
                //Send notification to the customer and to the assigned artisans
                //AAAAAAA
                addParagraph(translate("Done"));
                addButtonLink(translate("Add another image"),"addImagesToThisProject.php?id=".urlencode($insertedProjectId));
              }
            } else {
              addParagraph(translate("You have missed to select the new image"));
            }
          } else {
            addParagraph(translate("This project is already confirmed"));
          }
        } else {
          addParagraph(translate("You cant modify this project"));
        }
      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    }
  } else {
    //Page without post request
    if(isset($_GET["id"])){
      upperPartOfThePage(translate("Edit project"),"./project.php?id=".urlencode($_GET["id"]));
      if(doesThisProjectExists($_GET["id"])){
        //Verify that the user is who has created the project and that the project is not confirmed
        $projectInfos = obtainProjectInfos($_GET["id"]);
        if($_SESSION["userId"] == $projectInfos["designer"]){
          $thisProjectIsConfirmed = false;
          if($projectInfos["confirmedByTheCustomer"] == 1){
            $thisProjectIsConfirmed = true;
          }
          if(!$thisProjectIsConfirmed){
            //Content of this page
            addParagraph(translate("Project").": ".$projectInfos["name"]);
            //Title Edit project icon
            addTitle(translate("Add images to this project"));
            //Form to insert data to edit the icon of this project
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            addFileField(translate("Image"),"insertedImage");
            addHiddenField("insertedProjectId",$_GET["id"]);
            endForm(translate("Submit"));
            ?>
              <script>
                //form inserted parameters
                const form = document.querySelector('form');
                const inputFile = document.getElementById('formFile');
      
                function getFileExtension(filename){
                  return filename.substring(filename.lastIndexOf('.')+1, filename.length) || filename;
                }
      
                //prevent sending form with errors
                form.onsubmit = function(e){
                  if(inputFile.files[0]){
                    let file = inputFile.files[0];
                    let fileSize = file.size;
                    let fileName = file.name;
                    let fileExtension = getFileExtension(fileName);
                    const permittedExtensions = ["jpg","jpeg","gif","png","webp","heic"];
                    if(fileSize > <?= maxSizeForAFile ?>){
                      e.preventDefault();
                      alert("<?= translate("The file is too big") ?>");
                    } else if(!permittedExtensions.includes(fileExtension)){
                      e.preventDefault();
                      alert("<?= translate("The extension of the file is not an image") ?>");
                    }
                  } else {
                    alert("<?= translate("You have missed to select the new icon") ?>");
                    e.preventDefault();
                  }
                }
              </script>
            <?php
            //End main content of this page
          } else {
            addParagraph(translate("This project is already confirmed"));
          }
        } else {
          addParagraph(translate("You cant modify this project"));
        }
      } else {
        addParagraph(translate("This project doesnt exists"));
      }
    } else {
      //You have missed to specify the get param id of the project
      upperPartOfThePage(translate("Edit project"),"");
      addParagraph(translate("You have missed to specify the get param id of the project"));
    }
  }
  
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
