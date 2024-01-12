<?

include "../libs/embed/autoloader.php";



use Embed\Embed;


//Load any url:

$link = "https://www.youtube.com/watch?v=tMHhXzpwupU";
$info = Embed::create($link);


//Get content info:

/*

$info->title; //The page title
$info->description; //The page description
$info->url; //The canonical url
$info->type; //The page type (link, video, image, rich)
$info->tags; //The page keywords (tags)

$info->images; //List of all images found in the page
$info->image; //The image choosen as main image
$info->imageWidth; //The width of the main image
$info->imageHeight; //The height of the main image

$info->code; //The code to embed the image, video, etc
$info->width; //The width of the embed code
$info->height; //The height of the embed code
$info->aspectRatio; //The aspect ratio (width/height)

$info->authorName; //The resource author
$info->authorUrl; //The author url

$info->providerName; //The provider name of the page (Youtube, Twitter, Instagram, etc)
$info->providerUrl; //The provider url
$info->providerIcons; //All provider icons found in the page
$info->providerIcon; //The icon choosen as main icon

$info->publishedDate; //The published date of the resource
$info->license; //The license url of the resource
$info->linkedData; //The linked-data info (http://json-ld.org/)
$info->feeds; //The RSS/Atom feeds

*/

echo $info->title . "<br>";
echo $info->image . "<br>";

echo "<img src='$info->image' width='200' ><br>";



?>
