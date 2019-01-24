<template lang="html">
    <main class="columns" v-if="user">
        <div class="column is-one-third">
            <div class="header">{{ user.id_user == $store.state.user.id_user ? 'My profile' : user.name + '\'s profile' }}</div>
            <div class="avatar bg-img" v-bind:style="{ backgroundImage: `url(${user.full_img})` }"></div>
            <section class="head">
                <!-- <button>Edit Profile</button> -->
                <h1>{{ user.name }}</h1>
                <small>{{ user.job }}</small>
            </section>
            <section class="skills">
                <h3>Skills</h3>
                <table>
                    <tr class="job-row" v-for="item in user.skills">
                        <td class="name">{{ item.skill }}</td>
                        <td class="rating">
                            <svg v-for="n in Array.from({length: item.rating}, (x, i) => i)" width="20px" height="20px" enable-background="new 0 0 19.481 19.481" version="1.1" viewBox="0 0 19.481 19.481" xmlns="http://www.w3.org/2000/svg">
                                <path d="m10.201,.758l2.478,5.865 6.344,.545c0.44,0.038 0.619,0.587 0.285,0.876l-4.812,4.169 1.442,6.202c0.1,0.431-0.367,0.77-0.745,0.541l-5.452-3.288-5.452,3.288c-0.379,0.228-0.845-0.111-0.745-0.541l1.442-6.202-4.813-4.17c-0.334-0.289-0.156-0.838 0.285-0.876l6.344-.545 2.478-5.864c0.172-0.408 0.749-0.408 0.921,0z" fill="#604fe9"/>
                            </svg>
                        </td>
                    </tr>
                </table>
            </section>
        </div>
        <div class="column right">
            <section class="contacts">
                <h3>Contacts</h3>
                <div class="link-row">
                    <!-- <svg enable-background="new 0 0 512 512" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                		<path fill="#604FE9" class="st0" d="m60.1 5.5c-34.8 0-60.1 24-60.1 57s24.8 57 58.9 57c17.7 0 32.9-5.7 44.1-16.6 10.7-10.4 16.6-24.8 16.6-40.7-0.9-33.4-25.3-56.7-59.5-56.7zm27.3 81.5c-6.9 6.7-16.8 10.3-28.6 10.3-21.5 0-36.6-14.3-36.6-34.7 0-20.8 15.2-34.8 37.8-34.8 22.1 0 36.7 13.8 37.2 34.8 0 9.6-3.4 18.2-9.8 24.4z"/>
                		<path fill="#604FE9" class="st0" d="M10.7,506.5h100.1V138.4H10.7V506.5z M33,160.7h55.6v323.6H33V160.7z"/>
                		<path fill="#604FE9" class="st0" d="m374.9 138.4c-46.4 0-74.9 16-91.3 31.1l-3.1-31.1h-113.4v368.1h110.9v-193.5c0-8.3 3.4-25.6 5.5-30.2 13.4-28.8 31.5-28.8 58.9-28.8 31.5 0 58.2 29.6 58.2 64.6v188h111.4v-208c0-110.1-71.1-160.2-137.1-160.2zm114.8 345.8h-66.8v-165.7c0-47.1-36.9-86.9-80.5-86.9-28 0-59.7 0-79.1 41.8-4 8.6-7.6 29.3-7.6 39.6v171.2h-66.4v-323.5h70.9l4.5 45.1h18.3l3.3-5.3c7.4-12 30.9-39.8 88.5-39.8 55.3 0 114.9 43.1 114.9 137.9v185.6z"/>
                    </svg> -->
                    <p><a href="#">{{ user.email }}</a></p>
                </div>
                <div class="link-row">
                    <svg width="512px" height="512px" enable-background="new 0 0 473.806 473.806" version="1.1" viewBox="0 0 473.806 473.806" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                        <g fill="#604fe9">
                            <path d="m374.46 293.51c-9.7-10.1-21.4-15.5-33.8-15.5-12.3 0-24.1 5.3-34.2 15.4l-31.6 31.5c-2.6-1.4-5.2-2.7-7.7-4-3.6-1.8-7-3.5-9.9-5.3-29.6-18.8-56.5-43.3-82.3-75-12.5-15.8-20.9-29.1-27-42.6 8.2-7.5 15.8-15.3 23.2-22.8 2.8-2.8 5.6-5.7 8.4-8.5 21-21 21-48.2 0-69.2l-27.3-27.3c-3.1-3.1-6.3-6.3-9.3-9.5-6-6.2-12.3-12.6-18.8-18.6-9.7-9.6-21.3-14.7-33.5-14.7s-24 5.1-34 14.7l-0.2 0.2-34 34.3c-12.8 12.8-20.1 28.4-21.7 46.5-2.4 29.2 6.2 56.4 12.8 74.2 16.2 43.7 40.4 84.2 76.5 127.6 43.8 52.3 96.5 93.6 156.7 122.7 23 10.9 53.7 23.8 88 26 2.1 0.1 4.3 0.2 6.3 0.2 23.1 0 42.5-8.3 57.7-24.8 0.1-0.2 0.3-0.3 0.4-0.5 5.2-6.3 11.2-12 17.5-18.1 4.3-4.1 8.7-8.4 13-12.9 9.9-10.3 15.1-22.3 15.1-34.6 0-12.4-5.3-24.3-15.4-34.3l-54.9-55.1zm35.8 105.3c-0.1 0-0.1 0.1 0 0-3.9 4.2-7.9 8-12.2 12.2-6.5 6.2-13.1 12.7-19.3 20-10.1 10.8-22 15.9-37.6 15.9-1.5 0-3.1 0-4.6-0.1-29.7-1.9-57.3-13.5-78-23.4-56.6-27.4-106.3-66.3-147.6-115.6-34.1-41.1-56.9-79.1-72-119.9-9.3-24.9-12.7-44.3-11.2-62.6 1-11.7 5.5-21.4 13.8-29.7l34.1-34.1c4.9-4.6 10.1-7.1 15.2-7.1 6.3 0 11.4 3.8 14.6 7l0.3 0.3c6.1 5.7 11.9 11.6 18 17.9 3.1 3.2 6.3 6.4 9.5 9.7l27.3 27.3c10.6 10.6 10.6 20.4 0 31-2.9 2.9-5.7 5.8-8.6 8.6-8.4 8.6-16.4 16.6-25.1 24.4-0.2 0.2-0.4 0.3-0.5 0.5-8.6 8.6-7 17-5.2 22.7l0.3 0.9c7.1 17.2 17.1 33.4 32.3 52.7l0.1 0.1c27.6 34 56.7 60.5 88.8 80.8 4.1 2.6 8.3 4.7 12.3 6.7 3.6 1.8 7 3.5 9.9 5.3 0.4 0.2 0.8 0.5 1.2 0.7 3.4 1.7 6.6 2.5 9.9 2.5 8.3 0 13.5-5.2 15.2-6.9l34.2-34.2c3.4-3.4 8.8-7.5 15.1-7.5 6.2 0 11.3 3.9 14.4 7.3l0.2 0.2 55.1 55.1c10.3 10.2 10.3 20.7 0.1 31.3z"/>
                            <path d="m256.06 112.71c26.2 4.4 50 16.8 69 35.8s31.3 42.8 35.8 69c1.1 6.6 6.8 11.2 13.3 11.2 0.8 0 1.5-0.1 2.3-0.2 7.4-1.2 12.3-8.2 11.1-15.6-5.4-31.7-20.4-60.6-43.3-83.5s-51.8-37.9-83.5-43.3c-7.4-1.2-14.3 3.7-15.6 11s3.5 14.4 10.9 15.6z"/>
                            <path d="m473.26 209.01c-8.9-52.2-33.5-99.7-71.3-137.5s-85.3-62.4-137.5-71.3c-7.3-1.3-14.2 3.7-15.5 11-1.2 7.4 3.7 14.3 11.1 15.6 46.6 7.9 89.1 30 122.9 63.7 33.8 33.8 55.8 76.3 63.7 122.9 1.1 6.6 6.8 11.2 13.3 11.2 0.8 0 1.5-0.1 2.3-0.2 7.3-1.1 12.3-8.1 11-15.4z"/>
                        </g>
                    </svg>
                    <p><a href="#">{{ user.phone }}</a></p>
                </div>
                <div class="link-row">
                    <p><a href="#"></a></p>
                </div>

            </section>
            <section class="works">
                <h3>Work in:</h3>
                <div class="link-row">
                    <svg enable-background="new 0 0 512 512" version="1.1" viewBox="0 0 512 512" xml:space="preserve" xmlns="http://www.w3.org/2000/svg">
                    		<path d="m256 0c-99.252 0-180 80.748-180 180 0 33.534 9.289 66.26 26.869 94.652l142.88 230.26c2.737 4.411 7.559 7.091 12.745 7.091h0.119c5.231-0.041 10.063-2.804 12.75-7.292l139.24-232.49c16.61-27.792 25.389-59.681 25.389-92.22 0-99.252-80.748-180-180-180zm128.87 256.82l-126.59 211.37-129.9-209.34c-14.633-23.632-22.567-50.896-22.567-78.846 0-82.71 67.49-150.2 150.2-150.2s150.1 67.49 150.1 150.2c0 27.121-7.411 53.688-21.234 76.818z" fill="#604fe9"/>
                    		<path d="m256 90c-49.626 0-90 40.374-90 90 0 49.309 39.717 90 90 90 50.903 0 90-41.233 90-90 0-49.626-40.374-90-90-90zm0 150.2c-33.257 0-60.2-27.033-60.2-60.2 0-33.084 27.116-60.2 60.2-60.2s60.1 27.116 60.1 60.2c0 32.683-26.316 60.2-60.1 60.2z" fill="#604fe9"/>
                    </svg>
                    <p>{{ user.works_in }}</p>
                </div>
            </section>
            <section class="about">
                <h3>About</h3>
                <p>{{ user.about }}</p>
            </section>
            <section class="qual">
                <h3>Qualification</h3>
                <table>
                    <tr class="qual-row">
                        <td class="years">2012 - 2018</td>
                        <td class="value">
                            Therapist:
                            <span>Kaplan Medical Center</span>
                        </td>
                    </tr>
                </table>
            </section>
        </div>
    </main>
</template>

<script>
export default {
    data() {
        return {
            user: null
        };
    },
    methods: {
        sync() {
            if(this.$route.params.id_user) {
                axios.get('/user/info/' + this.$route.params.id_user).then(res => {
                    this.user = res.data;
                });
            } else {
                axios.get('/user/info').then(res => {
                    this.user = res.data;
                });
            }
        }
    },
    created() {
        this.sync();
    },
    watch: {
        $route() {
            this.sync();
        }
    }
}
</script>

<style lang="scss" scoped>

@import '~@/vars.scss';

main {
    h3 {
        letter-spacing: 1.2px;
        font-size: 20px;
        margin-bottom: 25px;
        margin-top: 0;
        text-transform: uppercase;
    }
    .is-one-third {
        padding-left: 80px;
        .header {
            padding-top: 70px;
            padding-bottom: 40px;
            color: $clr-d-pink;
            font-size: 40px;
            font-weight: bold;
        }
        .avatar {
            width: 320px;
            height: 320px;
            box-shadow: 0.8px 2.9px 6.9px 0.1px rgba(0, 0, 0, 0.22);
        }
        .head {
            button {
                margin-top: 20px;
                font-weight: 300;
                font-size: 14px;
                text-decoration: underline;
                padding: 0;
                border: 0;
            }
            h1 {
                font-size: 30px;
                width: 320px;
                text-align: center;
                margin-bottom: 0;
            }
            small {
                display: block;
                width: 320px;
                text-align: center;
                color: #979797;
                font-size: 20px;
            }
        }
        .skills {
            padding-top: 30px;
            .job-row {
                display: flex;
                flex-direction: row;
                .name {
                    flex-grow: 1;
                }
                .rating {
                    padding-left: 40px;
                    width: 125px;
                    svg {
                        margin: 0 2.5px;
                    }
                }
            }
        }
    }
    .right {
        margin-left: 140px;
        .link-row {
            margin: 15px 0;
            svg {
                display: block;
                float: left;
                width: 20px;
                height: auto;
            }
            p {
                margin: 0;
                margin-left: 40px;
                line-height: 1.4;
                a {
                    font-size: 16px;
                    color: $clr-black;
                }
            }
        }
        .contacts {
            margin-top: 180px;
            h3 {
                margin: 0;
            }
        }
        .works {
            padding-top: 25px;
            p {
                font-weight: 500;
            }
        }
        .about {
            padding-top: 25px;
        }
        .qual {
            padding-top: 25px;
            font-size: 16px;
            .years {
                &::before {
                    content: ' • ';
                }
            }
            .value {
                padding-left: 40px;
                font-weight: 600;
                span {
                    font-weight: 300;
                    color: #999999;
                }
            }
        }
    }
}

</style>
