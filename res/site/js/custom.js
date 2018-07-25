<<<<<<< HEAD
$('#fullname').click(function(){
    var name = $(this).text();
    $(this).html('');
    $('<input></input>')
        .attr({
            'type': 'text',
            'name': 'fname',
            'id': 'txt_fullname',
            'size': '30',
            'value': name
        })
        .appendTo('#fullname');
    $('#txt_fullname').focus();
});

$(document).on('blur','#txt_fullname', function(){
    var name = $(this).val();
    //alert('Make an AJAX call and pass this parameter >> name=' + name);
    $('#fullname').text(name);
=======
$('#fullname').click(function(){
    var name = $(this).text();
    $(this).html('');
    $('<input></input>')
        .attr({
            'type': 'text',
            'name': 'fname',
            'id': 'txt_fullname',
            'size': '30',
            'value': name
        })
        .appendTo('#fullname');
    $('#txt_fullname').focus();
});

$(document).on('blur','#txt_fullname', function(){
    var name = $(this).val();
    //alert('Make an AJAX call and pass this parameter >> name=' + name);
    $('#fullname').text(name);
>>>>>>> 49adfcc07c4d6884dc4b28ea00bfc3120e59a27b
});