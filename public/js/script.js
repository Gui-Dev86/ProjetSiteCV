/* menu burger */

$('#menuButton').click( function() {
    if ($('#menuMobile').is(":visible")) {
        $('#burgerIcon').show();
        $('#menuMobile, #closeIcon').hide();
    } else {
        $('#burgerIcon').hide();
        $('#menuMobile, #closeIcon').show();
    }
});

$('#menuMobile a').click( function() {
    $('#burgerIcon').show();
    $('#menuMobile, #closeIcon').hide();
});

/* h1 animation */
if (document.getElementsByClassName('text').length) {
  
  class TextScramble {
      constructor(el) {
        this.el = el
        this.chars = '!<>-_\\/[]{}—=+*^?#________'
        this.update = this.update.bind(this)
      }
      setText(newText) {
        const oldText = this.el.innerText
        const length = Math.max(oldText.length, newText.length)
        const promise = new Promise((resolve) => this.resolve = resolve)
        this.queue = []
        for (let i = 0; i < length; i++) {
          const from = oldText[i] || ''
          const to = newText[i] || ''
          const start = Math.floor(Math.random() * 100)
          const end = start + Math.floor(Math.random() * 100)
          this.queue.push({ from, to, start, end })
        }
        cancelAnimationFrame(this.frameRequest)
        this.frame = 0
        this.update()
        return promise
      }
      update() {
        let output = ''
        let complete = 0
        for (let i = 0, n = this.queue.length; i < n; i++) {
          let { from, to, start, end, char } = this.queue[i]
          if (this.frame >= end) {
            complete++
            output += to
          } else if (this.frame >= start) {
            if (!char || Math.random() < 0.28) {
              char = this.randomChar()
              this.queue[i].char = char
            }
            output += `<span class="dud">${char}</span>`
          } else {
            output += from
          }
        }
        this.el.innerHTML = output
        if (complete === this.queue.length) {
          this.resolve()
        } else {
          this.frameRequest = requestAnimationFrame(this.update)
          this.frame++
        }
      }
      randomChar() {
        return this.chars[Math.floor(Math.random() * this.chars.length)]
      }
    }
    
    const phrases = [
      'Développeur web'
    ]
    
    const el = document.querySelector('.text')
    
    const fx = new TextScramble(el)
    
    fx.setText(phrases[0])
  }

  var deleteLinks = document.querySelectorAll('.delete');
  
  for (var i = 0; i < deleteLinks.length; i++) {
    deleteLinks[i].addEventListener('click', function(event) {
      event.preventDefault();

      var choice = confirm(this.getAttribute('data-confirm'));

      if (choice) {
        window.location.href = this.getAttribute('href');
      }
    });
  }
