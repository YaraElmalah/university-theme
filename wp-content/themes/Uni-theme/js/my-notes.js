(function ($) {
  class myNotes {
    //1-Describe and create an object
    constructor() {
      //trigger the event
      this.events();
    }
    //2-Events
    events() {
      $("#my-notes").on("click", ".delete-note", this.deleteNote);
      $("#my-notes").on("click", ".edit-note", this.editNote.bind(this)); //we used bind here because there is a function inside function
      $("#my-notes").on("click", ".update-note", this.updateNote.bind(this));
      $(".submit-note").on("click", this.createNote.bind(this));
    }

    //3-Methods
    deleteNote(e) {
      //e.target => we get the parent now (html object )
      var thisNote = $(e.target).parents("li");
      $.ajax({
        beforeSend: (xhr) => {
          xhr.setRequestHeader("X-WP-Nonce", notes_obj.nonce);
        },
        url: uni_info.root_url + "/wp-json/wp/v2/note/" + thisNote.data("id"),
        type: "DELETE",

        success: (response) => {
          console.table(response);
          if (parseInt(response.userNoteCount) < 5) {
            $(".note-limit-message").removeClass("active");
          }
          thisNote.slideUp();
        },
        error: (response) => {
          console.table(response);
          if (response.userNoteCount < 5) {
            $(".note-message-limit").removeClass("active");
          }
        },
      });
    }
    editNote(e) {
      var thisNote = $(e.target).parents("li");
      if (thisNote.data("state") === "editable") {
        //make readonly
        this.makeNoteReadOnly(thisNote);
      } else {
        this.makeNoteEditable(thisNote);
      }
    }
    updateNote(e) {
      var thisNote = $(e.target).parents("li"),
        ourUpdatedData = {
          title: thisNote.find(".note-title-field").val(),
          content: thisNote.find(".note-body-field").val(),
        };
      $.ajax({
        beforeSend: (xhr) => {
          xhr.setRequestHeader("X-WP-Nonce", notes_obj.nonce);
        },
        url: uni_info.root_url + "/wp-json/wp/v2/note/" + thisNote.data("id"),
        type: "POST",
        data: ourUpdatedData,

        success: (response) => {
          this.makeNoteReadOnly(thisNote);
        },
        error: (response) => {
          console.log("Error");
        },
      });
    }
    createNote(e) {
      var thisNote = $(e.target).parents(".create-note"),
        ourData = {
          title: thisNote.find(".new-note-title").val(),
          content: thisNote.find(".new-note-body").val(),
          status: "publish",
        };
      $.ajax({
        beforeSend: (xhr) => {
          xhr.setRequestHeader("X-WP-Nonce", notes_obj.nonce);
        },
        url: uni_info.root_url + "/wp-json/wp/v2/note/",
        type: "POST", //if we changed this to get would get data, not creating data
        data: ourData,

        success: (response) => {
          /**
           * * Raw has fully control of the data
           */
          $(".new-note-title, .new-note-body").val("");
          $(`
              <li data-id="${response.id}">
          <input type="text" class="note-title-field" value="${response.title.raw}" readonly>
          <span class="edit-note">
            <i class="fa fa-pencil" aria-hidden="true"></i> Edit
          </span>
          <span class="delete-note">
            <i class="fa fa-trash-o" aria-hidden="true"></i> Delete
          </span>
          <textarea class="note-body-field" readonly>${response.content.raw}</textarea>
          <span class="update-note btn btn--blue btn--small">
            <i class="fa fa-arrow-right"></i>
          </span>

        </li>
          `)
            .prependTo("#my-notes")
            .hide()
            .slideDown();
          console.log("Congrats");
        },
        error: (response) => {
          console.log(response.responseText);
          if (response.responseText === "You have Reached Your Note Limit") {
            $(".note-limit-message").addClass("active");
          }
        },
      });
    }
    makeNoteEditable(thisNote) {
      thisNote
        .find(".note-title-field, .note-body-field")
        .removeAttr("readonly")
        .addClass("note-active-field");
      thisNote.find(".update-note").addClass("update-note--visible");
      thisNote.find(".edit-note").html('<i class="fa fa-times"></i> Cancel');
      thisNote.data("state", "editable");
    }
    makeNoteReadOnly(thisNote) {
      thisNote
        .find(".note-title-field, .note-body-field")
        .attr("readonly", "readonly")
        .removeClass("note-active-field");
      thisNote.find(".update-note").removeClass("update-note--visible");
      thisNote
        .find(".edit-note")
        .html(' <i class="fa fa-pencil" aria-hidden="true"></i> Edit');
      thisNote.data("state", "cancel");
    }
  }
  let studentNotes = new myNotes();
})(jQuery);
