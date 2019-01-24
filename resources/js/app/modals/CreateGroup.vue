<template lang="html">
    <modal
        name="create-group"
        :adaptive="true"
        height="auto"
        width="520"
    >
        <form @submit="create" class="content">
            <h3>Create group</h3>
            <input maxlength="32" required class="theme-input" placeholder="Group name" type="text" v-model="name">
            <div class="btn">
                <button type="submit" class="theme-button">Create</button>
            </div>
        </form>
    </modal>
</template>

<script>
export default {
    name: 'modal-registration',
    data() {
        return {
            name: '',
        };
    },
    methods: {
        create(e) {
            e.preventDefault();
            axios.post('/group', {
                name: this.name,
            }).then(res => {
                this.$router.push({ name: 'groups' });
                this.$modal.hide('create-group');
            }).catch(res => {
              alert(res.data.error);
            });
        }
    }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.content {
    padding: 50px 70px;
    h3 {
        font-size: 18px;
        text-align: center;
        margin: 0;
        margin-bottom: 50px;
    }
    .btn {
        padding: 40px 0;
        text-align: center;
    }
    .theme-input {
        margin: 10px 0;
    }
}

</style>
