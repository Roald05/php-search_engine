window.onload = function() {
    var inputTextArea = document.getElementById("inputTextAreaId");
    var outputTextArea = document.getElementById("outputTextAreaId");

    inputTextArea.onkeydown = function(evt) {
        evt = evt || window.event;
        var inputCommand = inputTextArea.value.substr(inputTextArea.value.lastIndexOf("\n")+1);
        if (evt.keyCode == 13) {
            inputTextArea.scrollTop=inputTextArea.scrollHeight;
            $.ajax({
                type: "POST",
                url: "../controller/search_engine.php",
                data: {inputCommand: inputCommand},
                success: function (result) {
                    outputTextArea.value = outputTextArea.value + "\n" + result;
                    outputTextArea.scrollTop=outputTextArea.scrollHeight;
                },
                error: function (result) {
                    alert('Error ' + result);
                }
            });
        }
    };
};