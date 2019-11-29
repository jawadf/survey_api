
var $collectionHolder;
var $collectionHolder2;

// setup an "add a question" button/link
var $addQuestionButton = $('<button type="button" class="btn btn-info">Add a question</button>');
var $addAnswerButton = $('<button type="button" class="btn btn-info">Add an answer</button>');
var $newLinkDiv = $('<div></div>').append($addQuestionButton);
var $newLinkDiv2 = $('<div></div>').append($addAnswerButton);


jQuery(document).ready(function() {
    // Get the ul that holds the collection of questions
    $collectionHolder = $('ul.questions');
    $collectionHolder2 = $('.answers-list');

    // add the "Add a question" anchor and li to the images ul
    $collectionHolder.append($newLinkDiv);
    $collectionHolder2.append($newLinkDiv2);

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });
    $collectionHolder2.find('li').each(function() {
        addTagFormDeleteLink($(this));
    });

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);
    $collectionHolder2.data('index', $collectionHolder2.find(':input').length);

    $addQuestionButton.on('click', function(e) {
        // add a new Image form (see next code block)
        addQuestionForm($collectionHolder, $newLinkDiv);
    });
    $addAnswerButton.on('click', function(e) {
        // add a new Image form (see next code block)
        addQuestionForm($collectionHolder2, $newLinkDiv2);
    });
});



function addQuestionForm($collectionHolder, $newLinkDiv) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your tags field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);


    // Display the form in the page in an li, before the "Add an image" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkDiv.before($newFormLi);

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

