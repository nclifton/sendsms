<template>
  <div id="app">
    <h1>Send a message</h1>
    <form @submit.prevent="onSubmit">
      <div>
        <label for="to">To Phone Number: </label>
        <input id="to" type="tel" v-model="form.to" required :pattern="toPattern">
      </div>
      <div>
        <label for="message"> Message: </label>
      </div>
      <div>
                <textarea
                  id="message"
                  type="text"
                  v-model="form.message"
                  :maxlength="765"
                  rows="5"
                  cols="40"
                  required
                ></textarea>
      </div>
      <div>
        <button type="submit">Send</button>
      </div>
      <div>
        {{ message }}
      </div>
    </form>
  </div>
</template>

<script>
  import axios from 'axios'

  export default {
    name: 'app',
    props: {
      toPrefix:{
        type: String,
        default: '61'
      },
      toPattern:{
        type: String,
        default: '61[0-9]{9}'
      }
    },
    data() {
      return {
        form: {
          to: null,
          message: null
        },
        message: null,
      }
    },
    created(){
      this.form.to = this.toPrefix;
    },
    watch: {
      "form.to": function () {
        // first characters will always be the to prefix,
        if (this.form.to.substring(0, this.toPrefix.length) !== this.toPrefix) {
          this.form.to = this.toPrefix + this.form.to.substring(2);
        }
        // remove any non-numerics
        this.form.to = this.form.to.replace(/[^0-9]/g,'');
      }
    },
    methods: {
      onSubmit() {
        axios
          .post('send.php', this.form)
          .then(response => this.message = response.data.message)
          .catch(error => {
            console.log(error);
            this.message = 'message failed';
          })
      },
    }
  }
</script>

<style lang="scss">

</style>
