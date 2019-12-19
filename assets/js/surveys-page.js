jQuery(document).ready(function() {

    $(".delete--button").click(function() {

        $(".survey-to-delete").html( `${$(this).data("survey-name")}`);
        $(".path-to-delete").attr( "href", `/admin/survey/${$(this).data("survey-id")}/delete` );
        
      });


});