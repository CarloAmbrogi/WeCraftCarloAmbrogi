<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Edit the icon of a project (if you are the designer of this project) by the id of the project
  //Only for not confirmed projects which will become unclaimed
  doInitialScripts();

  if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Receive post request for edit the icon of this project
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
            //Check on the file
            if(isset($_FILES['insertedIcon']) && $_FILES['insertedIcon']['error'] == 0){
              //You have chosen to send the file icon
              $fileName = $_FILES["insertedIcon"]["name"];
              $fileType = $_FILES["insertedIcon"]["type"];
              $fileSize = $_FILES['insertedIcon']['size'];
              $fileExtension = pathinfo($fileName, PATHINFO_EXTENSION);
              if($fileSize > maxSizeForAFile){
                addParagraph(translate("The file is too big"));
              } else if(!array_key_exists($fileExtension, permittedExtensions)){
                addParagraph(translate("The extension of the file is not an image"));
              } else if(!in_array($fileType, permittedExtensions)){
                addParagraph(translate("The file is not an image"));
              } else {
                //update the icon with the new file icon and make the project unclaimed
                $imgData = file_get_contents($_FILES['insertedIcon']['tmp_name']);
                changeIconOfAProject($insertedProjectId,$fileExtension,$imgData);
                //Send notification to the customer and to the assigned artisans
                sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$projectInfos["customer"],"The designer has applied some modifications to the project","project",$insertedProjectId);
                $previewArtisansToWitchIsAssignedThisProject = obtainPreviewArtisansToWitchIsAssignedThisProject($insertedProjectId);
                foreach($previewArtisansToWitchIsAssignedThisProject as &$singleArtisanPreview){
                  sendAutomaticMessageWithLink($_SESSION["userId"],"personal",$singleArtisanPreview["id"],"The designer has applied some modifications to the project","project",$insertedProjectId);
                }
                addParagraph(translate("Done"));
              }
            } else {
              addParagraph(translate("You have missed to select the new icon"));
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
            addTitle(translate("Edit project icon"));
            //Form to insert data to edit the icon of this project
            startForm1();
            startForm2($_SERVER['PHP_SELF']);
            addFileField(translate("Icon"),"insertedIcon");
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
