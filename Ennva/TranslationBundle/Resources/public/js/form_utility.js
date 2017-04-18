// Get the div that holds the collection of tags
var help_uri, help_file, help_youtube;
jQuery(document).ready(function() {
    // setup an "add a tag" link

    var $buttonAddValue = 'Aggiungi'; //default value
    var $buttonDelValue = 'Elimina'; //default value

    //caso insight:
    if($('ul').hasClass('tags insight')) {
    	var insightHolder = $('ul.tags.insight');
        var $buttonAddValue = 'Aggiungi approfondimento'; 
        var $buttonDelValue = 'Elimina'; 
    	var $addTagLink = $('<br><a href="#" class="add_tag_link btn controls">' + $buttonAddValue + '</a>');
    	var $newLinkLi = $('<div></div>').append($addTagLink);
    	// add the "add a tag" anchor and li to the tags ul
    	insightHolder.append($newLinkLi);

    $addTagLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addTagForm(insightHolder, $newLinkLi, $buttonDelValue);
    });

    insightHolder.find('li').each(function() {
        addTagFormDeleteLink($(this),$buttonDelValue);
        if($(this).find("input[type=hidden]").val() == '1'){
		$(this).find("input[id$='_uri']").parent().parent().hide();
	}else{
		$(this).find("input[id$='_file']").parent().parent().hide();
	}
    });
    }

    //caso media:
    if($('ul').hasClass('tags media')) {
    	var videoHolder = $('ul.tags.media');
        var $buttonAddValue = 'Aggiungi video'; 
        var $buttonDelValue2 = 'Elimina video'; 
    	var $addTagLink2 = $('<br><a href="#" class="add_tag_link btn controls">' + $buttonAddValue + '</a>');
    	var $newLinkLi2 = $('<div></div>').append($addTagLink2);
    	// add the "add a tag" anchor and li to the tags ul
    	videoHolder.append($newLinkLi2);

    $addTagLink2.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        addTagForm(videoHolder, $newLinkLi2, $buttonDelValue2);
    });

    videoHolder.find('li').each(function() {
        addTagFormDeleteLink($(this),$buttonDelValue2);
    });
    }

});

function setHelpMsg(msg1,msg2,msg3){
   help_uri = msg1;
   help_file = msg2;
   help_youtube = msg3;
}

function addTagForm(collectionHolder, $newLinkLi, $buttonDelValue) {

    // Get the data-prototype explained earlier
    var prototype = collectionHolder.attr('data-prototype');
    var pos = collectionHolder.find('li').last().find('input').last().attr('id');
    if (pos === undefined){
        pos = 0;
    }else{
        pos = pos.split('_');
        pos = parseInt(pos[pos.length - 2]) + 1;
    }
    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on the current collection's length.
    //var newForm = prototype.replace(/__name__/g, collectionHolder.children().length);
    var newForm = prototype.replace(/__name__/g, pos);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newFormLi.find("input[id$='"+pos+"_uri']").after("<span class='help-block'>"+help_uri+"</span>");
    $newFormLi.find("input[id$='"+pos+"_file']").after("<span class='help-block'>"+help_file+"</span>");
    $newFormLi.find("input[id$='"+pos+"_youtubeid']").after("<span class='help-block'>"+help_youtube+"</span>");
    addTagFormDeleteLink($newFormLi,$buttonDelValue);
    $newLinkLi.before($newFormLi);
    if($buttonDelValue == "Elimina")
    	hideUri(pos);
}

function addTagFormDeleteLink($tagFormLi,$buttonDelValue) {
    var $removeFormA = $('<a href="#" class="controls btn btn-small btn-danger" >' + $buttonDelValue + '</a><br><br>');
    $tagFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $tagFormLi.remove();
    });
}

function hideUri(id){
	$("input[id$='"+id+"_uri']").parent().parent().hide();
	var radio = "<div class='controls'><label class='radio inline'><input type='radio' name='optionsRadios"+id+"' id='inlineOptionsRadios1_"+id+"' value='option1' checked onclick='radioClick()' onchange='toggleUri("+id+",1)'>Inserisci file</label><label class='radio inline'><input type='radio' name='optionsRadios"+id+"' id='inlineOptionsRadios2_"+id+"' value='option2' onclick='radioClick()' onchange='toggleUri("+id+",0)'>inserisci Link</label></div>";
	$("input[id$='"+id+"_uri']").parent().parent().before(radio);
}

function toggleUri(id,hasfile){
	/*if($("#"+id+"").text() == "Carica un file"){
		$("#"+id+"").text("Inserisci url");
		$("input[id$='"+id+"_uri']").val("");
	}else{
		$("#"+id+"").text("Carica un file");
		$("input[id$='"+id+"_file']").val("");
	}*/
	$("input[id$='"+id+"_hasfile']").val(hasfile);
	$("input[id$='"+id+"_file']").val("");
	$("input[id$='"+id+"_uri']").val("");
	$("input[id$='"+id+"_uri']").parent().parent().toggle();
	$("input[id$='"+id+"_file']").parent().parent().toggle();

}
/////Hack per ie8
function radioClick()
{
 this.focus();  
}
