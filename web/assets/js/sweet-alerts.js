/*=========================================================================================
	File Name: sweet-alerts.js
	Description: A beautiful replacement for javascript alerts
	----------------------------------------------------------------------------------------
	Item Name: Modern Admin - Responsive Admin Theme
	Version: 3.0
	Author: Pixinvent
	Author URL: hhttp://www.themeforest.net/user/pixinvent
==========================================================================================*/
$(document).ready(function(){

	//my own
    $('#forgot-password').on('click',function(){
        swal("No to sorry!", "Jeśli chcesz odzyskać hasło skontaktuj się z adminem!", "warning");
    });

    $(document).on('click', '.cancel-delete', function(){
        var id = this.id.substr(7);
        alert(id);
        swal({
            title: "Jesteś pewny?",
            text: "Usunięcie tego urządzenia jest nieodwracalne!",
            icon: "warning",
            buttons: {
                cancel: {
                    text: "Nie, rozmyśliłem się",
                    value: null,
                    visible: true,
                    className: "",
                    closeModal: false,
                },
                confirm: {
                    text: "Tak, usuń to urządzenie!",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: false,
                }
            }
        })
            .then((isConfirm) => {
                if (isConfirm) {
                    deleteDevice(id);
                    swal("Usunięte!", "Tego urządzenia już nie zobaczysz.", "success");
                } else {
                    swal("Anulowano", "Twoje urządzenie pozostało", "error");
                }
            })

    });


	//default

	$('#basic-alert').on('click',function(){
		swal("Here's a message!");
	});

	$('#with-title').on('click',function(){
		swal("Here's a message!", "It's pretty, isn't it?");
	});

	$('#html-alert').on('click',function(){
		var el = document.createElement('span'),
		t = document.createTextNode("Custom HTML Message!!");
		el.style.cssText = 'color:#F6BB42';
		el.appendChild(t);
		swal({
			title: 'HTML Alert!',
			content: {
				element: el,
			}
		});
	});

	$('#type-success').on('click',function(){
		swal("Good job!", "You clicked the button!", "success");
	});

	$('#type-info').on('click',function(){
		swal("Info!", "You clicked the button!", "info");
	});



	$('#type-error').on('click',function(){
		swal("Error!", "You clicked the button!", "error");
	});

	$('#custom-icon').on('click',function(){
		swal({   title: "Sweet!",   text: "Here's a custom image.",   icon: "../../../app-assets/images/icons/thumbs-up.jpg" });
	});

	$('#auto-close').on('click',function(){
		swal({   title: "Auto close alert!",   text: "I will close in 2 seconds.",   timer: 2000,   showConfirmButton: false });
	});

	$('#outside-click').on('click',function(){
		swal({
			title: 'Click outside to close!',
			text: 'This is a cool message!',
			closeOnClickOutside: true
		});
	});

	$('#cancel-button').on('click',function(){
		swal({
		    title: "Are you sure?",
		    text: "You will not be able to recover this imaginary file!",
		    icon: "warning",
		    buttons: {
                cancel: {
                    text: "No, cancel plx!",
                    value: null,
                    visible: true,
                    className: "",
                    closeModal: false,
                },
                confirm: {
                    text: "Yes, delete it!",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: false
                }
		    }
		})
		.then((isConfirm) => {
		    if (isConfirm) {
		        swal("Deleted!", "Your imaginary file has been deleted.", "success");
		    } else {
		        swal("Cancelled", "Your imaginary file is safe", "error");
		    }
		});

	});

	$('#prompt-function').on('click',function(){
        swal("Write something interesting:", {
                content: "input",
            })
            .then((value) => {
            	if (value === false) return false;
			    if (value === "") {
			        swal("You need to write something!", "", "error");
			        return false;
			    }
                swal(`You typed: ${value}`);
            })

	});

	$('#ajax-request').on('click',function(){
        swal({
                text: 'Search for a movie. e.g. "La La Land".',
                content: "input",
                button: {
                    text: "Search!",
                    closeModal: false,
                },
            })
            .then(name => {
                if (!name) throw null;

                return fetch(`https://itunes.apple.com/search?term=${name}&entity=movie`);
            })
            .then(results => {
                return results.json();
            })
            .then(json => {
                const movie = json.results[0];

                if (!movie) {
                    return swal("No movie was found!");
                }

                const name = movie.trackName;
                const imageURL = movie.artworkUrl100;

                swal({
                    title: "Top result:",
                    text: name,
                    icon: imageURL,
                });
            })
            .catch(err => {
                if (err) {
                    swal("Oh noes!", "The AJAX request failed!", "error");
                } else {
                    swal.stopLoading();
                    swal.close();
                }
            });
	});

	$('#confirm-text').on('click',function(){
		swal({
		    title: "Confirm Button Text",
		    text: "See the confirm button text! Have you noticed the Change?",
		    icon: "warning",
		    buttons: {
                cancel: {
                    text: "No, cancel plx!",
                    value: null,
                    visible: true,
                    className: "",
                    closeModal: false,
                },
                confirm: {
                    text: "Text Changed!!!",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: false
                }
		    }
		}).then(isConfirm => {
		    if (isConfirm) {
		        swal("Changed!", "Confirm button text was changed!!", "success");
		    } else {
		        swal("Cancelled", "It's safe.", "error");
		    }
		});
	});

	$('#confirm-color').on('click',function(){
		swal({
		    title: "Are you sure?",
		    text: "You will not be able to recover this imaginary file!",
		    icon: "warning",
		    showCancelButton: true,
		    buttons: {
                cancel: {
                    text: "No, cancel plx!",
                    value: null,
                    visible: true,
                    className: "btn-warning",
                    closeModal: false,
                },
                confirm: {
                    text: "Yes, delete it!",
                    value: true,
                    visible: true,
                    className: "",
                    closeModal: false
                }
		    }
		}).then(isConfirm => {
		    if (isConfirm) {
		        swal("Deleted!", "Your imaginary file has been deleted.", "success");
		    } else {
		        swal("Cancelled", "Your imaginary file is safe :)", "error");
		    }
		});
	});

});