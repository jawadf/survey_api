# RestoSurvey App (API + Admin Panel)

RestoSurvey is a survey-building application, similar to surveymonkey and limesurvey, but tailored for the needs of local restaurants and businesses.
This project represents the backend of the application, including the API and the admin panel interface.

## Table Of Contents

1. [Installation](#Installation)
2. [General Structure](#general-structure)
3. [Usage](#usage)

## Installation

This project is built with Symfony 4.3, so you'll need to have all the prerequisites installed on your computer. Fortunately, Symfony has a very clear and detailed documentation on this topic (and all relevant topics really). Following [this link](https://symfony.com/doc/4.3/setup.html) you can find all the information on installing and setting up Symfony 4.3 on your computer.

Pay special attention to [this section](https://symfony.com/doc/current/setup.html#setting-up-an-existing-symfony-project), where they guide you on how to set up an existing Symfony project, like in our case.


After installing all the requirements, you are now ready to edit the project with a code editor. Run this command to have it running on localhost:8000

```
php bin/console server:start
```
*P.S.: Working on this app requires a good knowledge of the Symfony framework mainly, coupled with some other technologies such as jQuery and SQL. If you are not familiar with Symfony, you can check out the official documentation [here](https://symfony.com/doc/4.3/page_creation.html).*

## General Structure

This app has two main divisions: The API and the admin panel. They are clearly seperated in the 'src/Controller' folder, into 2 folders called 'Rest' and 'Web'. 'Rest' contains the controllers for the API, which is built using Symfony's FOSRestBundle; and the 'Web' folder contains the controller for the Admin Panel.

The configuration for these 2 folders can be found in the 'config/routes/annotations.yaml' file.

```
web_controller:
    resource: ../../src/Controller/Web/
    type: annotation


rest_controller:
    resource: ../../src/Controller/Rest/
    type: annotation
```

### The API

The API is built using Symfony's FOSRestBundle: all the controllers in the 'Rest' folder follow the same pattern of [manual defintion of routes](https://symfony.com/doc/master/bundles/FOSRestBundle/7-manual-route-definition.html).

Also, in all of the controllers' methods, we are working with data sent from the user in JSON format. Data is sent in the request's body, and we are using the following syntax to access them:
```
$content = json_decode($request->getContent(), true);
```
Most of the business logic is written in seperate files, more specifically, in [services](https://symfony.com/doc/4.3/service_container.html) defined inside 'src/Services'. This is to ensure reusability and keep our controllers 'thin'.

The user login and registration is handled by [Symfony's powerful API token authentication](https://symfony.com/doc/4.3/security/guard_authentication.html).

### The Admin Panel

This part of the backend, as opposed to the API, has an interface! And it's defined in [TWIG templates](https://symfony.com/doc/4.3/templates.html), inside the '/templates' folder. The styling in these templates is provided by the customizable [Metronic theme](https://keenthemes.com/metronic/preview/demo7/index.html).

![Screenshot of the Admin Panel](https://github.com/jawadf/survey_api/blob/master/assets/readme_images/admin-panel.png)

There is only one controller for the admin panel and it's '/src/Controller/Web/AdminController.php'. It is organized into 3 main sections: Survey Methods, User Methods and Business Methods. As the name shows, each section has a number of methods related to a specific entity in our application.

The 2 most common components in this panel are datatables and forms. The styling for both is provided by Metronic. Forms are built using [Symfony forms](https://symfony.com/doc/4.3/forms.html), and all the form 'types' exist in the 'src/Form' folder.

Note that some of the functionality of these forms, such as dynamically adding and removing fields to the form, is written on the front-end using jQuery, in the '/assets/js/add-collection-widget.js' file. The jQuery code here and the idea behind it are a bit complicated, but clear comments have been provided and related information can be found on Symfony's documentation: ["Allowing new tags with the prototype"](https://symfony.com/doc/4.3/form/form_collections.html#allowing-new-tags-with-the-prototype) and ["Symfony's Collection Type"](https://symfony.com/doc/4.3/reference/forms/types/collection.html).

As for the admin login functionality, we are using [Symfony's powerful security system](https://symfony.com/doc/4.3/security.html), the its related secure [login form](https://symfony.com/doc/4.3/security/form_login_setup.html).

## Usage

### The Admin Panel

The general purpose of the app is to create surveys, but in the process you'll be creating business/company profiles, adding users (managers or employees) to these companies, with the ability to edit or delete what you've created.

There is a clear and straightforward navigation on the top of the panel: 
![Screenshot of the Navigation](https://github.com/jawadf/survey_api/blob/master/assets/readme_images/admin-panel.png)
