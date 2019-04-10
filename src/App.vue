<template>
    <div id="app">
        <h1>Send a message</h1>
        <form @submit.prevent="onSubmit">
            <div>
                <label for="to">To Phone Number: </label>
                <input id="to" type="tel" v-model="form.to" required pattern="[+ 0-9]{14}">
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

        },
        data() {
            return {
                form: {
                    to: null,
                    message: null
                },
                message: null
            }
        },
        methods: {
            onSubmit() {
                axios
                    .post('send.php', this.form)
                    .then(response => this.message = response.data.message)
                    .catch(error => console.log(error))
            }
        }
    }
</script>

<style lang="scss">

</style>
