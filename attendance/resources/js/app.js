/**
 * First we will load all of this project's JavaScript dependencies which
 * includes Vue and other libraries. It is a great starting point when
 * building robust, powerful web applications using Vue and Laravel.
 */

require('./bootstrap');

window.Vue = require('vue');

/**
 * The following block of code may be used to automatically register your
 * Vue components. It will recursively scan this directory for the Vue
 * components and automatically register them with their "basename".
 *
 * Eg. ./components/ExampleComponent.vue -> <example-component></example-component>
 */

// const files = require.context('./', true, /\.vue$/i)
// files.keys().map(key => Vue.component(key.split('/').pop().split('.')[0], files(key).default))

Vue.component('example-component', require('./components/ExampleComponent.vue').default);

/**
 * Next, we will create a fresh Vue application instance and attach it to
 * the page. Then, you may begin adding components to this application
 * or customize the JavaScript scaffolding to fit your unique needs.
 */

// const app = new Vue({
//     el: '#app',
// });

var baseurl = location.origin + "/mypage";

var todo = new Vue({
  el:'#app',
  data:{
    list:[],
    last:null,
    inputText: "",
  },
  methods :{
    send: function() {
      if(!todo.last){
        todo.list = { created_at: "1970-01-01 00:00:00" }
      }

      sendMessage(this.inputText,todo.last.created_ad);//送信メッセージ関数
      this.inputText = "";  //初期化
    },
  }
});
function sendMessage(message) {
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    $.ajax({
        type: "POST",
        url: baseURL + '/send_message_api',
        dataType: "json",
        data: { "message": message, "created_at": created_at }
    }).done(function(results) {
        console.log("メッセージ追加成功！");
        console.log('results=',results);
        addMessage(results); // 通信に成功したらAPIからの返り値を addMessage() に入れる
    }).fail(function(jqXHR, textStatus, errorThrown) {
        alert('メッセージを送信に失敗しました。');
        console.log("ajax通信に失敗しました");
        console.log("jqXHR          : " + jqXHR.status);     // HTTPステータスが取得
        console.log("textStatus     : " + textStatus);       // タイムアウト、パースエラー
    });
};

getMessages(true);  //初回起動

function getMessages() {
  $ajaxSetup({
    headers: {'X-CSRF-TOKEN': $(meta[name="csrf-token"]).attr('content')}
  });

  $.ajax({
    type:"POST",
    url: baseurl + '/mypage/',
    dataType: "json",
    data: { "created_ad" :data}
  }).done(function(results){
    addMessage(results);
  }).fail(function(jqXHR, textStatus, errorThrown) {
  alert('メッセージの取得に失敗しました。');
  console.log("ajax通信に失敗しました");
  console.log("jqXHR          : " + jqXHR.status);     // HTTPステータスが取得
  console.log("textStatus     : " + textStatus);       // タイムアウト、パースエラー
  });

}
function addMessage(results){
  $each(results.tasks, function() {
    todo.list.push(this);
    todo.last = this;
  });
}