<?php

  //Useful costants

  //WeCraft base url
  define('WeCraftBaseUrl', "http://carloambrogipolimi.altervista.org/WeCraft/");

  //Max size for an uploaded file
  define('maxSizeForAFile', 5 * 1024 * 1024);

  //Permitted extensions for an uploaded file
  define('permittedExtensions', array("jpg" => "image/jpg", "jpeg" => "image/jpeg", "gif" => "image/gif", "png" => "image/png", "webp" => "image/webp", "heic" => "image/heic"));

  //An image representing a generic user (for users without an image)
  define('genericUserImage', "http://carloambrogipolimi.altervista.org/WeCraft/Icons/genericUser.jpg");

  //An image representing a generic product (for products without an image)
  define('genericProductImage', "http://carloambrogipolimi.altervista.org/WeCraft/Icons/genericProduct.jpg");

  //Possible categories of a product
  define('categories', ["Jewerly","Home decoration","Pottery","Teppiches","Bedware Bathroomware","Artisan craft"]);

  //Days in a week
  define('daysInAWeek', ["Mon","Tue","Wed","Thu","Fri","Sat","Sun"]);

?>
