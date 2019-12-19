<?php
namespace App\Service;

use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Validation;
use Symfony\Component\Validator\Constraints\Image as Img;

class UploadImageService
{

    public function upload(/*UploadedFile $file, */ $request, $user)
    {
            // Get the uploaded image
            $uploaded = $request->files->get('registration_form')['upload'];
            
            if($uploaded !== null) {
            

                    // Validate image
                    if(!array_search($uploaded->guessExtension(), ['jpg', 'jpeg', 'png'])) {
                        throw new Exception('Not a jpeg or png');
                        }
                    $validator = Validation::createValidator();
                    $violations = $validator->validate($uploaded, [
                        new Img([
                            'maxWidth' => '1500', 
                            'maxHeight' => '1000',
                            'detectCorrupted' => true,
                            'mimeTypes' => [
                                'image/jpeg',
                                'image/png',
                            ],
                            'mimeTypesMessage' => 'Please upload a valid JPG file'
                        ]),
                    ]);

                    // Get the filename without the extension
                    //$originalFilename = pathinfo($uploaded->getClientOriginalName(), PATHINFO_FILENAME);
                    // generate unique ID + guess the extension
                    $newFilename = uniqid().'.'.$uploaded->guessExtension();
                    // set the picture property on the relevant user entity
                    $user->setPicture($newFilename);

                    // Move file to 'public/uploads' directory
                    //$imagesDirectory = $this->getParameter('uploads_directory');
                    try {
                        $uploaded->move(
                            'uploads_directory',
                            $newFilename
                        );
                     } catch (FileException $e) {
                                echo 'Caught exception: ',  $e->getMessage(), "\n";
                    }         
                
                
            }
    }
}