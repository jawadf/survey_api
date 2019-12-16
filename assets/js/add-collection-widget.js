
var $collectionHolder;
var $collectionHolder2; 

var $questionCount = 1;

// setup an "add a question" button/link
var $addQuestionButton = $('<button type="button" class="btn btn-info"><i class="flaticon-add-circular-button"></i>Add a question</button>');
var $addAnswerButton = $('<button type="button" class="btn btn-success"><i class="flaticon-add-circular-button"></i>Add an answer</button>');

var $addQuestionDiv = $('<div></div>').append($addQuestionButton);
var $addAnswerDiv = $('<div></div>').append($addAnswerButton);

jQuery(document).ready(function() {

    // Get the ul that holds the collection of questions
    $collectionHolder = $('ul.questions');
    $collectionHolder2 = $('.answers-list');

    // add the "Add a question" anchor and li to the images ul
    $collectionHolder.append($addQuestionDiv);

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('a.card-delete').each(function() {
        addTagFormDeleteLink($(this));
    });

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);    

    $addQuestionButton.on('click', function(e) {
        // add a new Question form 
        addQuestionForm($collectionHolder, $addQuestionDiv);
    });

    // Conditionally render the 'Add answers' functionality 
    renderAnswerOptions($collectionHolder2, $addAnswerButton, $addAnswerDiv, 0);

    // Add the 'jquery select2' functionality
    $('.js-select-user').select2();

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

    // increase the questions' counter by one
    $questionCount++;

    var $newFormLi = $(`
    <!--begin::Accordion-->
    <div class="accordion my-accordion accordion-solid accordion-toggle-plus" id="accordionExample${$questionCount}">
        <a class="card-delete"></a>
		<div class="card">
			<div class="card-header" id="headingOne${$questionCount}">
				<div class="card-title" data-toggle="collapse" data-target="#collapseOne${$questionCount}" aria-expanded="true" aria-controls="collapseOne${$questionCount}">
					<i class="flaticon-pie-chart-1"></i> Question ${$questionCount}
				</div>
			</div>
			<div id="collapseOne${$questionCount}" class="collapse show" aria-labelledby="headingOne${$questionCount}" data-parent="#accordionExample${$questionCount}">
                <div class="card-body">
                    ${newForm}
				</div>
			</div>
		</div>
	</div>
	<!--end::Accordion-->
    `);
    //.append(newForm);
    $addQuestionDiv.before($newFormLi);


    // add a delete link to the new form
    $newFormLi.find('a.card-delete').each(function() {
        addTagFormDeleteLink($(this));
    });
    // addTagFormDeleteLink($newFormLi);

    // Add answer feature
    /******************************************************************************/
    $collectionHolder2 = $('.answers-list');
     var prototype2 = $collectionHolder2.data('prototype');

     prototype2 = prototype2.replace(/0/g, index);

     var $addNewAnswerButton = $('<button type="button" class="new btn btn-success"><i class="flaticon-add-circular-button"></i>Add an answer</button>');
     var $addNewAnswerDiv = $('<div></div>').append($addNewAnswerButton);

    //var $collectionHolder3 = $("<ul class='form-group answers-list'  data-prototype='"+prototype2+"'></ul>");
    var $collectionHolder3 = $("#survey_questions_"+index+"_answers");
    $collectionHolder3.data('prototype', prototype2);

    // Conditionally render the 'Add answers' functionality
    renderAnswerOptions($collectionHolder3, $addNewAnswerButton, $addNewAnswerDiv, index);

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
    var $newFormLi2 = $('<div></div>').append(newForm2);
    $addDiv.before($newFormLi2);


    // add a delete link to the new form
    addAnswerDeleteLink($newFormLi2);
}


function addTagFormDeleteLink($formLi) {
    var $removeFormButton = $('<i class="flaticon-delete"></i>');
    $formLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $formLi.remove();
    });
}

function addAnswerDeleteLink($formLi) {
    var $removeFormButton = $('<i class="flaticon-delete-1 delete-answer-button"></i>');
    $formLi.append($removeFormButton);

    $removeFormButton.on('click', function(e) {
        // remove the li for the tag form
        $formLi.remove();
    });
}

function renderAnswerOptions($collection, $button, $div, index) {

    $(`#survey_questions_${index}_answer_type`).change(function () {
        // If we DO need to render options
        if($(this).val()=="multiple" || $(this).val()=="single" || $(this).val()=="dropdown"){

            // Add answer feature
            $collection.append($div);
            $collection.data('index', $collection.find(':input').length);
            $collection.find('li').each(function() {
                addAnswerDeleteLink($(this));
            });
            $button.on('click', function(e) {
                addAnswerForm($collection, $div);
           });

           $collection.find(':input').attr("placeholder", "Type here to search");

        // If we do NOT need to render options
        } else {
            $collection.children().remove();
        }
    })
    .change();
}
