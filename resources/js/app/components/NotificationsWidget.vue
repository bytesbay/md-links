<template lang="html">
    <div class="notifications" v-bind:class="{ opened: opened }">
        <button @click="opened = !opened" class="header-btn">
            <div class="num">
                4
            </div>
        </button>
        <transition
            enter-active-class="animated fadeInLeft"
            leave-active-class="animated fadeOutRight"
        >
            <div class="popup" v-click-outside="hide" v-if="opened">
                <div class="item" v-for="n in [
                    {
                        name: 'New Patient: Rebecca James',
                        time: 'Now'
                    },
                    {
                        name: 'Oscar Terra canceled his visit at 11:00 am',
                        time: '5 minute ago'
                    },
                    {
                        name: 'Peter Russell tests are ready',
                        time: '1 hour ago'
                    },
                    {
                        name: 'Stefan Zimmerman asked to change the time of the visit',
                        time: '2 hour ago'
                    }
                ]">
                    <div class="title">
                        {{ n.name }}
                    </div>
                    <div class="time">
                        {{ n.time }}
                    </div>
                </div>
            </div>
        </transition>
    </div>
</template>

<script>
import ClickOutside from 'vue-click-outside'

export default {
    name: 'notifications-widget',
    data() {
        return {
            opened: false,
        };
    },
    methods: {
        hide() {
            this.opened = false;
        },
    },
    directives: {
        ClickOutside
    }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

.notifications {
    position: relative;
    button {
        height: 40px;
        background-image: url('data:image/svg+xml;base64,PD94bWwgdmVyc2lvbj0iMS4wIiBlbmNvZGluZz0iaXNvLTg4NTktMSI/PjxzdmcgeG1sbnM9Imh0dHA6Ly93d3cudzMub3JnLzIwMDAvc3ZnIiB4bWxuczp4bGluaz0iaHR0cDovL3d3dy53My5vcmcvMTk5OS94bGluayIgdmVyc2lvbj0iMS4xIiBpZD0iTGF5ZXJfMSIgeD0iMHB4IiB5PSIwcHgiIHZpZXdCb3g9IjAgMCA0NTkuMzM0IDQ1OS4zMzQiIHN0eWxlPSJlbmFibGUtYmFja2dyb3VuZDpuZXcgMCAwIDQ1OS4zMzQgNDU5LjMzNDsiIHhtbDpzcGFjZT0icHJlc2VydmUiIHdpZHRoPSI1MTJweCIgaGVpZ2h0PSI1MTJweCI+PGc+PGc+PGc+PHBhdGggZD0iTTE3NS4yMTYsNDA0LjUxNGMtMC4wMDEsMC4xMi0wLjAwOSwwLjIzOS0wLjAwOSwwLjM1OWMwLDMwLjA3OCwyNC4zODMsNTQuNDYxLDU0LjQ2MSw1NC40NjEgICAgIHM1NC40NjEtMjQuMzgzLDU0LjQ2MS01NC40NjFjMC0wLjEyLTAuMDA4LTAuMjM5LTAuMDA5LTAuMzU5SDE3NS4yMTZ6IiBmaWxsPSIjZmZmZmZmIi8+PHBhdGggZD0iTTQwMy41NDksMzM2LjQzOGwtNDkuMDE1LTcyLjAwMmMwLTIyLjA0MSwwLTc1Ljg5OCwwLTg5LjgzYzAtNjAuNTgxLTQzLjE0NC0xMTEuMDc5LTEwMC4zODEtMTIyLjQ1OVYyNC40ODUgICAgIEMyNTQuMTUyLDEwLjk2MywyNDMuMTksMCwyMjkuNjY3LDBzLTI0LjQ4NSwxMC45NjMtMjQuNDg1LDI0LjQ4NXYyNy42NjNjLTU3LjIzNywxMS4zODEtMTAwLjM4MSw2MS44NzktMTAwLjM4MSwxMjIuNDU5ICAgICBjMCwyMy43MTYsMCw3Ni4wODQsMCw4OS44M2wtNDkuMDE1LDcyLjAwMmMtNS4xNjMsNy41ODQtNS43MDksMTcuNDAxLTEuNDE5LDI1LjUxMWM0LjI5LDguMTEsMTIuNzEyLDEzLjE4MiwyMS44ODcsMTMuMTgyICAgICBIMzgzLjA4YzkuMTc1LDAsMTcuNTk3LTUuMDczLDIxLjg4Ny0xMy4xODJDNDA5LjI1OCwzNTMuODM5LDQwOC43MTEsMzQ0LjAyMiw0MDMuNTQ5LDMzNi40Mzh6IiBmaWxsPSIjZmZmZmZmIi8+PC9nPjwvZz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PGc+PC9nPjxnPjwvZz48Zz48L2c+PC9zdmc+');
        .num {
            position: absolute;
            width: 16px;
            height: 16px;
            border-radius: 8px;
            background: #e94f4f;
            color: #fff;
            text-align: center;
            line-height: 16px;
            font-size: 12px;
            right: 5px;
            top: 5px;
            font-weight: 500;
        }
    }
    .popup {
        right: -20px;
        width: 400px;
        top: 60px;
        position: absolute;
        box-shadow: 1.3px 4.8px 29px 0 rgba(0, 0, 0, 0.2);
        background-color: #fff;
        .item {
            padding: 20px 30px;
            padding-left: 50px;
            font-weight: 300;
            position: relative;
            &:hover {
                background-color: $clr-pink;
                &::before {
                    background-color: $clr-d-pink;
                }
            }
            &::before {
                content: '';
                position: absolute;
                width: 10px;
                height: 10px;
                border-radius: 5px;
                left: 20px;
                top: 50%;
                margin-top: -5px;
                background-color: #b8b8b8;
            }
            .title {
                font-size: 15px;
            }
            .time {
                color: #a0a3ab;
                font-size: 13px;
            }
        }
    }
}

</style>
