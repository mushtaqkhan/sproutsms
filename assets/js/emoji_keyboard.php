 <script> 
  $(document).ready(function() {
    const jsonUrl = "<?php echo ru ?>assets/js/emoji.json";
    //  const jsonUrl = "https://gist.githubusercontent.com/housamz/67087a81eaf78837a420fdef4accf263/raw/emojis.json";
    const categoryButtonsContainer = $(".category-buttons");
    const emojiContainer = $(".emoji-container"); 
    const historyButton = $(".history-button");
    const searchInput = $(".search-input");
    let currentCategory = null;
    let data;

    // Fetch the JSON data
    $.getJSON(jsonUrl, function(parsedData) {
      data = parsedData;

      const emojis = data.emojis;
      const categories = Array.from(new Set(emojis.map(emoji => emoji.category)));

      // Create category buttons with emojis 
        categories.forEach(category => {
            const categoryButton = $("<button>")
                .addClass("category-button")  // Add "btn btn-link" class
                .attr("type", "button")                   // Add "type" attribute
                .click(() => filterEmojisByCategory(category));
        
            const categoryEmoji = emojis.find(emoji => emoji.category === category);
            categoryButton.html(categoryEmoji ? categoryEmoji["#text"] : category);
        
            categoryButtonsContainer.append(categoryButton);
        });

      // Display recent emojis or the "smileys_people" category by default
      const recentEmojis = getRecentEmojis();
      if (recentEmojis.length > 0) {
        displayEmojis(recentEmojis);
      } else {
        displayEmojis(emojis.filter(emoji => emoji.category === "smileys_people"));
      }
    })
    .fail(function(error) {
      console.error("Error fetching emojis:", error);
    });

    historyButton.click(() => displayRecentEmojis());
    searchInput.on("input", () => handleSearch());

    function filterEmojisByCategory(category) {
      currentCategory = category;
      const filteredEmojis = data.emojis.filter(emoji => emoji.category === category);
      displayEmojis(filteredEmojis);
    }

    function displayEmojis(emojis) {
      emojiContainer.empty();
      emojis.forEach(emoji => {
        const button = $("<button>")
          .addClass("emoji-button")
          .text(emoji["#text"])
          .attr("title", emoji.title)
          .attr("type", "button")
          .click(() => {
            insertEmojiAtCursor(emoji["#text"]);
            addToRecentEmojis(emoji);
          });

        emojiContainer.append(button);
      });
    }

    function getRecentEmojis() {
      const recentEmojisJSON = localStorage.getItem("recentEmojis");
      return recentEmojisJSON ? JSON.parse(recentEmojisJSON) : [];
    }

    function addToRecentEmojis(emoji) {
      const recentEmojis = getRecentEmojis();
      const updatedRecentEmojis = [emoji, ...recentEmojis.filter(e => e["#text"] !== emoji["#text"])].slice(0, 50);
      localStorage.setItem("recentEmojis", JSON.stringify(updatedRecentEmojis));
    }

    function displayRecentEmojis() {
      const recentEmojis = getRecentEmojis();
      if (recentEmojis.length > 0) {
        displayEmojis(recentEmojis);
      } else {
        displayEmojis(emojis.filter(emoji => emoji.category === "smileys_people"));
      }
    }

    function handleSearch() {
      const searchTerm = searchInput.val().toLowerCase();
      const filteredEmojis = data.emojis.filter(emoji =>
        emoji.title.toLowerCase().includes(searchTerm) ||
        emoji.keywords.toLowerCase().includes(searchTerm)
      );
      displayEmojis(filteredEmojis);
    }
    
    let textarea = null;
    $(document).on('click','.change_input', function(){
       var input_type = $(this).data('input');
         textarea = $('.'+ input_type);
    }); 
    function insertEmojiAtCursor(emoji) {
        if (!textarea || !textarea.length) {
            return;
        }
    
        if (textarea.is('input') || textarea.is('textarea')) {
            const cursorPos = textarea.prop("selectionStart");
            const textBeforeCursor = textarea.val().substring(0, cursorPos);
            const textAfterCursor = textarea.val().substring(cursorPos);
            textarea.val(textBeforeCursor + emoji + textAfterCursor);
            textarea.focus();
            const newCursorPos = cursorPos + emoji.length;
            textarea.prop("selectionStart", newCursorPos);
            textarea.prop("selectionEnd", newCursorPos);
        }
        else if (textarea.is('div') && textarea.hasClass('emoji_update')) {
            textarea.html(emoji); 
            const emojiUpdateContainer = textarea.closest('.emoji_update');
    
            if (emojiUpdateContainer.length > 0) {
                const groupId = emojiUpdateContainer.data('id');  
                console.log('Sending id to process:', groupId);
                // emojiUpdateContainer.find('.groupEmoji').html(emoji); 
                var formData = { 'emoji': emoji, 'groupId': groupId, 'action':'updateEmoji' };
                $.ajax({
                    type: 'POST',
                    url: 'process/process_group.php',
                    data: formData,
                    success: function(response) { 
                    } 
                });
            }  
        }
        else if (textarea.is('div') || textarea.is('button')  && !textarea.hasClass('emoji_update') ) {
             textarea.html(emoji);   
        }
        else { 
                textarea.html(emoji); 
                var phone_number = $('#phone_number').val();
                if (phone_number !== undefined && phone_number !== '') {
                    console.log('Sending data to server:', emoji, phone_number);
                    $.ajax({
                        type: 'POST',
                        url: 'process/process_number_setting.php',
                        data: { emoji: emoji, phone_number: phone_number },
                        success: function(response) { 
                        } 
                    });
                } 
        }
    }
  });
    
</script> 
 <script>
  $(document).ready(function() {
    const categoryButtonsContainer = $(".category-buttons");

    // Open the dropdown without closing on click inside
    $(".btn-dropup .dropdown-menu").on("click", function(e) {
      e.stopPropagation();
    });

    // Close the dropdown when clicking outside
    $(document).on("click", function(e) {
      if ($(".btn-dropup").hasClass("show")) {
        $(".btn-dropup").removeClass("show");
        $(".dropdown-menu").removeClass("fixed");
      }
    });

    // Toggle the fixed position when dropdown is shown
    $(".btn-dropup").on("shown.bs.dropdown", function() {
      $(".dropdown-menu").addClass("fixed");
    });

    // Fetch and display emojis here...
  });
</script>
 