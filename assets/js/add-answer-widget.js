
var $collectionHolder2;

// setup an "add a question" button/link
var $addAnswerButton = $('<button type="button" class="btn btn-info">Add an answer</button>');
var $addAnswerDiv = $('<div></div>').append($addAnswerButton);


jQuery(document).ready(function() {
    // Get the ul that holds the collection of questions
    $collectionHolder2 = $('ul.answers-area');

    // add the "Add a question" anchor and li to the images ul
    $collectionHolder2.append($addAnswerDiv);

    // add a delete link to all of the existing tag form li elements
    
    $collectionHolder2.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder2.data('index', $collectionHolder2.find(':input').length);

  
     $addAnswerButton.on('click', function(e) {
         // add a new Answer form 
        addAnswerForm($collectionHolder2, $addAnswerDiv);
     });
});


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
    var $newFormLi = $('<li></li>').append(newForm);
    $addQuestionDiv.before($newFormLi);


    // add a delete link to the new form
    addTagFormDeleteLink($newFormLi);
}



function addTagFormDeleteLink($formLi) {
    var $removeFormButton = $('<button class="btn btn-danger" type="button">X</button>');
    $formLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $formLi.remove();
    });
}

