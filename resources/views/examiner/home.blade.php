@extends('layouts.app')

@section('content')

    <div class="container" id="examinerHome">
        <div v-if="works.length != 0">
            <p style="margin-top: 20px" align="center">Работы</p>
            <div style="margin-bottom: 20px" v-for="(item,key) in works">
                <a style="margin-left: 20px" href="#" @click="item.toggle=!item.toggle">@{{item.groupName+" "+item.studName}}</a>
                <div style="display: inline-block; float:right; margin-right: 10px">
                    <div style="display: inline-block;margin-left: 10px;" v-show="!item.edit" @click="item.edit=!item.edit"
                         class="btn-sm btn-primary"><i
                                class="fa fa-pencil" aria-hidden="true"></i></div>
                    <div v-show="item.edit" class="input-group mb-3">
                        <input v-model="item.newNote" type="text" class="form-control" aria-label="Name"
                               aria-describedby="button-addon2">
                        <div class="input-group-append">
                            <button @click="send(item)" class="btn btn-outline-secondary" type="button"
                                    id="button-addon2">ОК
                            </button>
                        </div>
                    </div>
                </div>
                <div style="margin-left: 50px; margin-top: 15px" v-if="item.toggle===true">
                    <table class="table table-sm" border="0">
                        <tbody>
                        <tr>
                            <td width="200px">Имя руководителя</td>
                            <td>
                                <span >@{{item.leaderName}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td width="200px">Тема на английском</td>
                            <td>
                                <span >@{{item.themeEn}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Тема на украинском</td>
                            <td>
                                <span >@{{item.themeUkr}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Дата защиты</td>
                            <td>
                                <span>@{{item.date}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Записка</td>
                            <td>
                                <div v-show="item.file !== null && !item.editFile">
                                    <a id="link" style="display: inline-block;" :href="'/download/'+item.file"><i
                                                class="icon-download-alt"> </i>Скачать&nbsp;</a>
                                </div>
                            </td>
                        </tr>
                        <tr>
                            <td>Количество страниц в записке</td>
                            <td>
                                <span>@{{item.realPages}}</span>
                            </td>
                        </tr>
                        <tr>
                            <td>Количество слайдов в презентации</td>
                            <td>
                                <span>@{{item.graphicPages}}</span>
                            </td>
                        </tr>
                        <tr v-if="item.r1n !== null">
                            <td>Первый рецензент</td>
                            <td>
                                <span><strong>Имя: </strong>@{{ item.r1n }}</span>
                                <p></p>
                                <span><strong>Место работы: </strong>@{{ item.r1w }}</span>
                                <p></p>
                                <span><strong>Должность: </strong> @{{ item.r1p }}</span>
                                <p></p>
                                <span><strong>Научная степень: </strong> @{{ item.r1d }}</span>
                            </td>
                        </tr>
                        <tr v-if="item.r2n !== null">
                            <td>Второй рецензент</td>
                            <td>
                                <span><strong>Имя: </strong>@{{ item.r2n }}</span>
                                <p></p>
                                <span><strong>Место работы: </strong>@{{ item.r2w }}</span>
                                <p></p>
                                <span><strong>Должность: </strong> @{{ item.r2p }}</span>
                                <p></p>
                                <span><strong>Научная степень: </strong> @{{ item.r2d }}</span>
                            </td>
                        </tr>
                        <tr v-if="item.prot !== null">
                            <td>Протокол</td>
                            <td>
                                <h3 style="margin-left: 30%">@{{item.prot}}</h3>
                            </td>
                        </tr>
                        <tr v-if="item.rate !== null">
                            <td>Оценка</td>
                            <td>
                                <h3 style="margin-left: 30%">@{{item.rate}}</h3>
                            </td>
                        </tr>
                        <tr v-if="item.rate !== null">
                            <td>Оценка в национальной шкале</td>
                            <td>
                                <h3 style="margin-left: 30%" v-if="item.rate<60">Незадовільно</h3>
                                <h3 style="margin-left: 30%" v-if="item.rate>=60 && item.rate<75">Задовільно</h3>
                                <h3 style="margin-left: 30%" v-if="item.rate>=75 && item.rate<90 ">Добре</h3>
                                <h3 style="margin-left: 30%" v-if="item.rate>90">Відмінно</h3>
                            </td>
                        </tr>
                        <tr v-if="item.rate !== null">
                            <td>Оценка в европейской шкале</td>
                            <td>
                                <h3 style="margin-left: 30%" v-if="item.rate<60">F</h3>
                                <h3 style="margin-left: 30%" v-if="item.rate>=60 && item.rate<75">E</h3>
                                <h3 style="margin-left: 30%" v-if="item.rate>=75 && item.rate<90 ">C</h3>
                                <h3 style="margin-left: 30%" v-if="item.rate>90 && item.rate<96 ">B</h3>
                                <h3 style="margin-left: 30%" v-if="item.rate>96">A</h3>
                            </td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div v-else>
            <span>Работ пока нет</span>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        var vExaminer = new Vue({
            el: '#examinerHome',
            data: {
                works: [],
            },
            methods: {
                send: function (item) {
                    if (item.newNote!=="") {
                        let data = {data: item}
                        this.$http.post('/api/makeExaminerNote', data)
                            .then(function () {
                                alert("Готово!");
                            });
                    }
                    item.edit = false;
                },

            },
            created: function () {
                var _this = this;
                this.$http.get('/api/getExaminerWorks')
                    .then(function (response) {
                        _this.works = response.data.works;
                    });
            }
        });
    </script>
@endpush