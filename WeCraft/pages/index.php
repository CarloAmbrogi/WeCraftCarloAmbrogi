<?php
  include "./../components/includes.php";
  include "./../functions/includes.php";
  include "./../database/access.php";
  include "./../database/functions.php";

  //Home page of WeCraft
  doInitialScripts();
  $kindOfTheAccountInUse = getKindOfTheAccountInUse();
  upperPartOfThePage(translate("Welcome to WeCraft"),"");
  //if you are logged in, you are redirect to your starting page (according if you are a customer or an artisan or a designer)
  // Title Welcome to WeCraft
  addTitle(translate("Welcome to WeCraft"));
  if($kindOfTheAccountInUse == "Guest"){
    addButtonLink(translate("Go to register log in page"),"./account.php");
  } else {
    $userInfos = obtainUserInfos($_SESSION["userId"]);
    $nameAndSurname = $userInfos["name"]." ".$userInfos["surname"];
    addParagraph(translate("Welcome")." ".$nameAndSurname);
  }
  //Content of the home page
  addParagraphUnsafe(text("introduction"));
  //Categories buttons
  addTitle(translate("Categories"));
  startCardGrid();
  addACardForTheGrid("./AAAAAAAAAAAA",WeCraftBaseUrl."Icons/JewerlyIcon.png",translate("Jewerly"),"","");
  addACardForTheGrid("./AAAAAAAAAAAA",WeCraftBaseUrl."Icons/HomeDecorationIcon.png",translate("Home decoration"),"","");
  addACardForTheGrid("./AAAAAAAAAAAA",WeCraftBaseUrl."Icons/PotteryIcon.png",translate("Pottery"),"","");
  addACardForTheGrid("./AAAAAAAAAAAA",WeCraftBaseUrl."Icons/TeppichesIcon.png",translate("Teppiches"),"","");
  addACardForTheGrid("./AAAAAAAAAAAA",WeCraftBaseUrl."Icons/BedwareBathroomwareIcon.png",translate("Bedware Bathroomware"),"","");
  addACardForTheGrid("./AAAAAAAAAAAA",WeCraftBaseUrl."Icons/ArtisanCraftIcon.png",translate("Artisan craft"),"","");
  endCardGrid();
  //Some products
  addTitle(translate("Some products"));
  //AAAAAAAAAA
  //End of the page
  lowerPartOfThePage(tabBarForTheAccountInUse());
  include "./../database/closeConnectionDB.php";
?>
