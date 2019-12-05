
var $collectionHolder;
var $collectionHolder2;


// setup an "add a question" button/link
var $addQuestionButton = $('<button type="button" class="btn btn-info">Add a question</button>');
var $addAnswerButton = $('<button type="button" class="btn btn-info">Add an answer</button>');

var $addQuestionDiv = $('<div></div>').append($addQuestionButton);
var $addAnswerDiv = $('<div></div>').append($addAnswerButton);

jQuery(document).ready(function() {

    // Get the ul that holds the collection of questions
    $collectionHolder = $('ul.questions');

    // add the "Add a question" anchor and li to the images ul
    $collectionHolder.append($addQuestionDiv);

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $collectionHolder2 = $('.answers-list');
    $collectionHolder2.append($addAnswerDiv);
   $collectionHolder2.data('index', $collectionHolder2.find(':input').length);
    $collectionHolder2.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });
    

    $addQuestionButton.on('click', function(e) {
        // add a new Question form 
        addQuestionForm($collectionHolder, $addQuestionDiv);

        
    });

     $addAnswerButton.on('click', function(e) {
         //add a new Answer form 
         addAnswerForm($collectionHolder2, $addAnswerDiv);
    });

});


/**
 * 
 * In this function we are trying to recreate the Question form
 * as soon as the user clicks on 'Add Question'
 * 
 * 
 * But as part of the process, we also need to recreate the 'Add an answer' functionality
 * 
 */

function addQuestionForm($collectionHolder, $addQuestionDiv) {

    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');
    
    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add an image" link li
    
    /**
     * NOTE: try to access an inner div and then append,
     *       because we need to recreate the same as the first form 
     */

    var $newFormLi = $('<li></li>').append(newForm);
    $addQuestionDiv.before($newFormLi);


    // add a delete link to the new form
    addTagFormDeleteLink($newFormLi);


    /******************************************************************************/
    $collectionHolder2 = $('.answers-list');
     var prototype2 = $collectionHolder2.data('prototype');

     prototype2 = prototype2.replace(/0/g, index);

     var $addNewAnswerButton = $('<button type="button" class="new btn btn-info">Add an answer</button>');
     var $addNewAnswerDiv = $('<div></div>').append($addNewAnswerButton);

    //var $collectionHolder3 = $("<ul class='form-group answers-list'  data-prototype='"+prototype2+"'></ul>");
    var $collectionHolder3 = $("#survey_questions_"+index+"_answers");
    $collectionHolder3.data('prototype', prototype2);
    $collectionHolder3.append($addNewAnswerDiv);


     $collectionHolder3.find('li').each(function() {
         addTagFormDeleteLink($(this));
     });
     $collectionHolder3.data('index', $collectionHolder3.find(':input').length);
     $addNewAnswerButton.on('click', function(e) {
         addAnswerForm($collectionHolder3, $addNewAnswerDiv);
     });
   /*******************************************************************************/
  
}


 function addAnswerForm($collection, $addDiv) {
     // Get the data-prototype explained earlier
     var prototype2 = $collection.data('prototype');

     // get the new index
     var index2 = $collection.data('index');

     var newForm2 = prototype2;
     // You need this only if you didn't set 'label' => false in your tags field in TaskType
     // Replace '__name__label__' in the prototype's HTML to
     // instead be a number based on how many items we have
     newForm2 = newForm2.replace(/__name__/g, index2);

     // increase the index with one for the next item
     $collection.data('index', index2 + 1);


     // Display the form in the page in an li, before the "Add an image" link li
     var $newFormLi2 = $('<li></li>').append(newForm2);
     $addDiv.before($newFormLi2);


     // add a delete link to the new form
     addTagFormDeleteLink($newFormLi2);
 }


function addTagFormDeleteLink($formLi) {
    var $removeFormButton = $('<button class="btn btn-danger" type="button">X</button>');
    $formLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $formLi.remove();
    });
}
