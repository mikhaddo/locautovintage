/**
 * this is a bug-fix for bootstrap 4 form
 * when we select a file, nothing appears on the input
 * migration jQuery -> javaScript vanilla
 * @Author Titus
 */
document.querySelector('.custom-file-input').addEventListener('change', function(){
    // by défault 'this.value' return 'C:\\fakepath\\image.jpg'
    // split('\\') make array with elements 'C:'; 'fakepath'; and 'image.jpg'
    // .pop() keep only the last element of the array
    let fileName = this.value.split('\\').pop();

    // the nextElement is <label>
    this.nextElementSibling.classList.add('selected');
    this.nextElementSibling.textContent = fileName;
});

// old crappy jQuery
// $(".custom-file-input").on("change", function() {
//     var fileName = $(this).val().split("\\").pop();
//     $(this).siblings(".custom-file-label").addClass("selected").html(fileName);
// });