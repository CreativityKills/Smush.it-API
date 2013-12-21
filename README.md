# Smush.it API Library

This is a library that makes it easy to use the [http://smushit.com](Smush.it) API on your projects. Usage is easy, first install the library by unzipping to your library directory or better still using composer:

## Requirements
 - JSON extension
 - cURL extension

## Installation

### Using  Composer

Add this to your composer file:

    "require" : {
    	"neo/hooks" : "dev-master"
    }

Then you can now run your <code>composer install</code> command and let it install all the dependencies.

## Usage

To smush an image you should do this:

	$original_image = 'http://placehold.it/300x300';

	try
	{
	    $response = Neo\Smushit\Smushit::make($original_image);

		$resized = $response->dest;
	}
	catch(Neo\Smushit\Exception\SmushitException $e)
	{
    	$resized = $original_image;
	}

Here is a sample response that can help you decode exactly how to handle various responses:

#### Successful response

    {
    	"src":"http://placehold.it/300x300",
    	"src_size":1200,
    	"dest":"http://ysmushit.zenfs.com/results/c1c4ef27%2Fsmush%2F300x300.png",
    	"dest_size":921,
    	"percent":"23.25",
    	"id":""
    }

#### Unsuccessful response

    {
    	"src":"http://placehold.it",
    	"error":"Could not get the image",
    	"id":""
    }

