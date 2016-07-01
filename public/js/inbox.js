/*
 * +===============================================
 * | Author:        Parham Alvani (parham.alvani@gmail.com)
 * |
 * | Creation Date: 29-06-2016
 * |
 * | File Name:     inbox.js
 * +===============================================
 */
window.onload = onInboxLoad

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
  }
})

var inbox = new Vue({
  el: '#home',
  data: {
    mails: [],
    limit: 5,
    offset: 0
  }
})

var compose = new Vue({
  el: '#compose',
  data: {
    hasError: false,
    errors: {
      recipient: '',
      title: '',
      content: ''
    }
  }
})

var pagination = new Vue({
  el: '#pagination',
  data: {
    from: 0,
    to: 0,
    total: 0,
    nextUrl: null,
    backUrl: null
  },
  methods: {
    next: function () {
      if (this.nextUrl != null) {
        fetchMail(this.nextUrl)
      }
    },
    back: function () {
      if (this.backUrl != null) {
        fetchMail(this.backUrl)
      }
    }
  }
})

function onInboxLoad () {
  $('#compose-recipient').blur(checkMailExistance)
  $('#compose-send').click(sendMail)
  $('#compose-content').summernote();
  fetchMail()
}

function fetchMail (url) {
  if (typeof url === 'undefined') {
    var url = '/TMail/mail'
  }
  $.ajax({
    type: 'GET',
    url: url,
    dataType: 'json',
    success: function (response) {
      pagination.to = response.to
      pagination.from = response.from
      pagination.total = response.total
      pagination.nextUrl = response.next_page_url
      pagination.backUrl = response.perv_page_url
      var mails = response.data
      inbox.mails = inbox.mails.concat(mails)
    }
  })
}

function checkMailExistance () {
}

function sendMail () {
  var form = {
    'recipient': $('#compose-recipient').val(),
    'title': $('#compose-title').val(),
    'content': $('#compose-content').summernote('code')
  }
  $.ajax({
    type: 'POST',
    url: '/TMail/mail',
    data: form,
    dataType: 'json',
    success: function (msg) {
      compose.hasError = false
      $('#compose-recipient').val('')
      $('#compose-title').val('')
      $('#compose-content').val('')
      $('#compose').modal('hide')
    },
    error: function (msg) {
      compose.hasError = true
      compose.errors = msg.responseJSON
    }
  })
}
