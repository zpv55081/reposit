<template>
  <main-menu />
  <div>
    <windows-popup v-if="windowsPopup.show" :title="windowsPopup.title" :content="windowsPopup.content"
      :main_color="windowsPopup.main_color" :toggle-windows-popup="() => toggleWindowsPopup()" />
  </div>

  <notifications class="mx-auto p-3" />

  <div class="container">
    <h2 class="mb-3">
      Панель оперативного контроля
    </h2>
    <div class="d-flex justify-content-center align-items-center mb-5">
      <p class="mb-0 me-3 small">
        Данные рассчитаны на момент 05:00(Yekt) 17.03.2023г.
      </p>
      <b-button style="width:220px;" variant="primary" size="sm" :disabled="isSending">
        <b-spinner v-if="isSending" small />
        <span v-if="isSending">
          Ожидание ответа...
        </span>
        <span v-else>
          Обновить расчёт (~3мин)
        </span>
      </b-button>
    </div>

    <div class="row d-flex align-items-center mb-5 bg-light">
      <div class="col-12 col-sm">
        <b-form-select v-model="selected_cluster" :options="clusters_options" size="sm" class="" />
      </div>
      <div class="col-12 col-sm">
        <b-form-select v-model="selected_period" :options="periods_options" size="sm" class="" />
      </div>
      <div class="col-12 col-sm">
        <div class="d-flex align-items-center justify-content-center pt-3">
          <b-form-group label="">
            <b-form-checkbox-group v-model="selected_forecast" :options="forecast_options" switches class="" />
          </b-form-group>
        </div>
      </div>
    </div>

    <div>
      <div class="border rounded-top mb-4">
        <div v-b-toggle.accordion-indicators-vols variant="light" class="d-flex bg-light p-2 rounded-top">
          <div class="col text-start">
            <span class="h4">Ключевые показатели по строительству и эксплуатации ВОЛС</span>
          </div>
          <div class="col text-end text-muted align-bottom pointer">
            Нажмите чтобы развернуть/свернуть
          </div>
        </div>
        <b-collapse id="accordion-indicators-vols" visible accordion="my-accordion" role="tabpanel" class="border-top">
          <div v-if="!isLoaded" class="m-3">
            <div class="alert alert-info" role="alert">
              <div class="d-flex justify-content-center my-3">
                <div class="spinner-border" role="status" />
              </div>
              Пожалуйста подождите. Данные загружаются.
            </div>
          </div>
          <div class="container px-5 py-3">
            <b-table hover bordered small :items="itm" class="text-start" />
          </div>
          <div>
            <div v-if="isLoaded" class="">
              <div class="container">
                <div class="row">
                  <div class="col-6" align="left">

                    <p>
                      Аварийность сети, шт. на 1000 абонентов
                    </p>
                    <div id="network_failure_ta_fa" />
                  </div>
                  <div class="col-6" align="left">
                    <p>
                      Аварийность сети (оптическая), шт. на 1000 абонентов
                    </p>
                    <div id="network_failure_optic" />
                  </div>
                </div>
              </div>
              <br><br>
              <div class="container">
                <div class="row">
                  <div class="col-6" align="left">

                    <p>
                      Доступность сети, %
                    </p>
                    <div id="network_availability_ta_fa" />
                  </div>
                  <div class="col-6" align="left">
                    <p>
                      Недоступность сети (простой оборудования), часов
                    </p>
                    <div id="network_nonavailability" />
                  </div>
                </div>
              </div>
              <br><br>
              <div class="container">
                <div class="row">
                  <div class="col-6" align="left">

                    <p>
                      Актуализации сетей (магистральные сети), км
                    </p>
                    <div id="target_actualization_ma" />
                  </div>
                  <div class="col-6" align="left">
                    <p>
                      Актуализации сетей (домашние сети), км
                    </p>
                    <div id="target_actualization_ho" />
                  </div>
                </div>
                <br><br>
                <div class="container">
                  <div class="row">
                    <div class="col-6" align="left">

                      <p>Помесячный график (строек выполнено)</p>
                      <div id="mesyach" />
                    </div>
                    <div class="col-6" align="left">
                      <div id="act_network_home" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </b-collapse>
      </div>

      <div class="border rounded-top mb-5">
        <div v-b-toggle.accordion-indicators-btu class="d-flex bg-light p-2 rounded-top">
          <div class="col text-start">
            <span class="h4">Ключевые показатели по состоянию базы технического учета</span>
          </div>
          <div class="col text-end text-muted align-bottom pointer">
            Нажмите чтобы развернуть/свернуть
          </div>
        </div>
        <b-collapse id="accordion-indicators-btu" visible accordion="my-accordion" role="tabpanel" class="border-top">
          <div v-if="!isLoaded" class="m-3">
            <div class="alert alert-info" role="alert">
              <div class="d-flex justify-content-center my-3">
                <div class="spinner-border" role="status" />
              </div>
              Пожалуйста подождите. Данные загружаются.
            </div>
          </div>
          <div>
            <div v-if="isLoaded" class="">
              <div class="container">
                <div>
                  <b-form-group label="">
                    <b-form-checkbox-group v-model="sel" :options="opt" switches />
                  </b-form-group>
                </div>
                <div class="row">
                  <div class="col-6" align="left">

                    <p>
                      Перенос данных (ВОЛС), км
                    </p>
                    <div id="target_transposition_vols" />
                  </div>
                  <div class="col-6" align="left">
                    <p>
                      Перенос данных (ОМ), шт.
                    </p>
                    <div id="target_transposition_om" />
                  </div>
                </div>
              </div>
              <br><br>
              <div class="container">
                <div class="row">
                  <div class="col-6" align="left">

                    <p>
                      Перенос данных (ОК), шт.
                    </p>
                    <div id="target_transposition_ok" />
                  </div>
                  <div class="col-6" align="left">
                    <p>
                      Занесение данных по новому строительству, %
                    </p>
                    <div id="zdpnspr" />
                  </div>
                </div>
              </div>
              <br><br>
              <div class="container">
                <div class="row">
                  <div class="col-6" align="left">

                    <p>
                      Занесение данных по новому строительству, км
                    </p>
                    <div id="zdpnskm" />
                  </div>
                  <div class="col-6" align="left">
                    <p>
                      Кабельных линий, км.
                    </p>
                    <div id="kablinkm" />
                  </div>
                </div>
                <br><br>
                <div class="container">
                  <div class="row">
                    <div class="col-6" align="left">

                      <p>Кабельных линий, шт.</p>
                      <div id="kablinsht" />
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </b-collapse>
      </div>
      <div class="d-inline">
        <button class="btn btn-secondary m-2" type="button" data-bs-toggle="collapse" data-bs-target="#collapseExample"
          aria-expanded="false" aria-controls="collapseExample" @click.prevent="getDataForm">
          Отображение отладочной информации
        </button>
      </div>
      <div>
        <div id="collapseExample" class="collapse">
          <div class="p-3 text-start">
            <pre class="bg-secondary text-white p-3"><p>aop_projects_number</p>{{ aop_projects_number }}</pre>
            <pre class="bg-secondary text-white p-3"><p>aor_projects_number</p>{{ aor_projects_number }}</pre>
            <pre class="bg-secondary text-white p-3"><p>indicator_inf</p>{{ indicator_inf }}</pre>
          </div>
        </div>
      </div>
      <default-footer />
    </div>
  </div>
</template>
<script>
import { ref } from 'vue';

import { mapActions, mapGetters } from 'vuex';

// Главное меню
import mainMenu from "@/components/menu/MainMenu.vue";

// Стандартный подвал
import defaultFooter from "@/components/footer/DefaultFooter.vue";

// base css
import "../../../node_modules/billboard.js/dist/theme/insight.css";
import bb from "../../../node_modules/billboard.js/dist/billboard";

// Миксин для запросов на бэкенд
import request from "@/mixins/api/common/request";

export default {
  name: "TheDashboard",
  components: {
    mainMenu,         // Главное меню
    defaultFooter,    // Стандартный подвал
  },

  mixins: [
    request, // Миксин для запросов на бэкенд
  ],

  setup() {

    const windowsPopup = ref({
      show: false,
      title: '',
      content: '',
    });

    const toggleWindowsPopup = (
      is_show,
      title,
      content,
      main_color = 'success',
    ) => {
      windowsPopup.value['show'] = is_show;
      windowsPopup.value['title'] = title;
      windowsPopup.value['content'] = content;
      windowsPopup.value['main_color'] = main_color;
      //goBack(is_show);
    };

    return {
      windowsPopup,
      toggleWindowsPopup,
      //goBack,
      isSending: false, //
    };

  },
  data() {
    return {
      aop_projects_number: null,
      aor_projects_number: null,

      itm: null,

      selected_cluster: null,
      clusters_options: [
        { value: null, text: 'Все кластеры' },
        { value: 'a', text: 'Центр' },
        { value: 'b', text: 'Север' },
        { value: 'nw', text: 'Северо-Запад' },
        { value: 'w', text: 'Запад' },
        { value: 'e', text: 'Восток' },
        { value: 'nn', text: 'Нейва' },
        { value: 'P', text: 'Пермь', disabled: true },
        { value: { C: '3PO' }, text: 'Крым' },
      ],

      selected_period: new Date().setFullYear(new Date().getFullYear() - 10),
      periods_options: [
        { value: new Date().setFullYear(new Date().getFullYear() - 1), text: '1 год' },
        { value: new Date().setFullYear(new Date().getFullYear() - 2), text: '2 года' },
        { value: new Date().setFullYear(new Date().getFullYear() - 3), text: '3 года' },
        { value: new Date().setFullYear(new Date().getFullYear() - 4), text: '4 года' },
        { value: new Date().setFullYear(new Date().getFullYear() - 5), text: '5 лет' },
        { value: new Date().setFullYear(new Date().getFullYear() - 10), text: '10 лет' },
      ],

      selected_forecast: [],
      forecast_options: [
        { text: 'Прогноз текущего месяца', value: 'progn' },
      ],

      sel: [],
      opt: [
        { text: 'Накопительный', value: 'nakop' },
      ],

      indicator_inf: [],
      network_failure_ta_fa_inf: null,
      network_failure_ta_fa_inf_dat1x_str: null,
      network_failure_ta_fa_inf_ndat1x_str: null,
      network_failure_ta_fa_inf_dat1y_str: null,
      network_failure_ta_fa_inf_ndat1y_str: null,
      network_failure_optic_inf: null,
      network_availability_ta_fa_inf: null,
      network_availability_ta_fa_inf_data1: null,
      network_availability_ta_fa_inf_data2: null,
      network_availability_ta_fa_inf_x1: null,
      network_availability_ta_fa_inf_x2: null,
      network_nonavailability_inf: null,
      target_actualization_ma_inf: null,
      target_actualization_ho_inf: null,
      target_transposition_ok_inf: null,
      target_transposition_om_inf: null,
      target_transposition_vols_inf: null,

    };
  },

  computed: {
    // Кластера
    ...mapGetters("clusters", ["CLUSTERS"]),
  },

  watch: {
    selected_period() {
      this.buildGraphs();
    },
  },

  created: function () {

    // Получим данные для страницы

    this.request('dashboard', {}, {}, {}, (r) => {
      this.aop_projects_number = r.data.aop_projects_number;
      this.aor_projects_number = r.data.aor_projects_number;

      this.itm = [
        { Показатель: 'Задач по строительству в очереди', Значение: 123 },
        {
          Показатель: ' Задач по строительству выполняется',
          Значение: this.aop_projects_number,
          _rowVariant: 'info',
        },
        {
          Показатель: 'Отчетов на проверке',
          Значение: this.aor_projects_number,
          _cellVariants: { Показатель: 'warning', Значение: 'warning' },
        },
        { Показатель: 'Аварий в текущий момент ', Значение: 32 },
      ];
    });

    this.request('indicator', {}, {}, {}, (r) => {

      this.isLoaded = true;   // скрываем спиннер, данные грфиков загружены

      // без задержки графики рендерятся внизу страницы, потому что им нужен построенный ДОМ-элемент
      setTimeout(() => {
        // Исправление ошибки, #132 - "Проблема с графиком."
        // ВНИМАТЕЛЬНО. Данные загружаются асинхронно, и рендерятся после получения данных.
        // Условие (this.$route.path == '/dashboard'), код выполняется, если находишься на странице '/dashboard'
        if (this.$route.path == '/dashboard') {
          this.indicator_inf = r.data;
          this.network_failure_ta_fa_inf = r.data.network_failure_ta_fa;
          this.network_failure_ta_fa_inf_dat1x_str = r.data.network_failure_ta_fa.dat1x_str;
          this.network_failure_ta_fa_inf_ndat1x_str = r.data.network_failure_ta_fa.ndat1x_str;
          this.network_failure_ta_fa_inf_dat1y_str = r.data.network_failure_ta_fa.dat1y_str;
          this.network_failure_ta_fa_inf_ndat1y_str = r.data.network_failure_ta_fa.ndat1y_str;

          this.network_failure_optic_inf = r.data.network_failure_optic;

          this.network_availability_ta_fa_inf = r.data.network_availability_ta_fa;
          this.network_availability_ta_fa_inf_data1 = this.network_availability_ta_fa_inf.Среднее.ordinates,
            // eslint-disable-next-line max-len
            this.network_availability_ta_fa_inf_data2 = this.network_availability_ta_fa_inf.Ср__по_компании_цель.ordinates,
            this.network_availability_ta_fa_inf_x1 = this.network_availability_ta_fa_inf.Среднее.abscisses,
            // eslint-disable-next-line max-len
            this.network_availability_ta_fa_inf_x2 = this.network_availability_ta_fa_inf.Ср__по_компании_цель.abscisses,

            this.network_nonavailability_inf = r.data.network_nonavailability;

          this.target_actualization_ma_inf = r.data.target_actualization_ma;

          this.target_actualization_ho_inf = r.data.target_actualization_ho;

          this.target_transposition_ok_inf = r.data.target_transposition_ok;

          this.target_transposition_om_inf = r.data.target_transposition_om;

          this.target_transposition_vols_inf = r.data.target_transposition_vols;
        }

        this.buildGraphs();
      }, 10);
    });
  },
  mounted() {
  },
  updated() {

  },

  methods: {
    // Кластеры
    ...mapActions("clusters", ["GET_CLUSTERS_FROM_API"]),

    buildGraphs() {
      if (this.$route.path == '/dashboard') {
        const network_failure_ta_fa = bb.generate({
          data: {
            xs: {
              data1: "x1",
              data2: "x2",
            },
            json: {
              // eslint-disable-next-line max-len
              data1: ((this.network_failure_ta_fa_inf_dat1y_str.split(', ').map(Number))),
              // eslint-disable-next-line max-len
              x1: ((this.network_failure_ta_fa_inf_dat1x_str.split(', ').map(String))),
              // eslint-disable-next-line max-len
              data2: ((this.network_failure_ta_fa_inf_ndat1y_str.split(', ').map(Number))),
              // eslint-disable-next-line max-len                      
              x2: ((this.network_failure_ta_fa_inf_ndat1x_str.split(', ').map(String))),
            },
            type: "line", // for ESM specify as: area()
            colors: {
              data1: "steelBlue",
              data2: "orange",
            },
            xFormat: "%m-%d-%Y",
          },
          axis: {
            x: {
              tick: {
                fit: false,
                count: 5,
              },
              type: "timeseries",
            },
          },
          point: {
            focus: {
              only: true,
            },
          },
          zoom: {
            enabled: true,
            linked: true,
          },
          tooltip: {
            linked: true,
          },
          bindto: "#network_failure_ta_fa",
        });
        network_failure_ta_fa.resize({
          width: 500,
          height: 400
          ,
        });
        network_failure_ta_fa.data.names({
          data1: "Среднее",
          data2: "Среднее целевых",
        });

        let n_f_o_starting_period_index = null;
        // eslint-disable-next-line max-len
        for (const [key, value] of Object.entries(this.network_failure_optic_inf.Оптическая_авария.abscisses)) {
          if (this.selected_period <= value) {
            n_f_o_starting_period_index = key;
            break;
          }
        }

        console.log('n_f_o_starting_period_index');
        console.log(n_f_o_starting_period_index);

        // eslint-disable-next-line max-len
        var network_failure_optic_inf_x1_mdYs = this.network_failure_optic_inf.Оптическая_авария.abscisses.slice(n_f_o_starting_period_index).map(function (element) {
          var dateObject = new Date(element);
          var year_number = dateObject.getFullYear();
          var month_number = dateObject.getMonth() + 1;
          var day_number = dateObject.getDate();
          var humanDateFormat = month_number + "-" + day_number + "-" + year_number;
          return humanDateFormat;
        });
        console.log(333777);
        console.log(network_failure_optic_inf_x1_mdYs);

        const network_failure_optic = bb.generate({
          data: {
            xs: {
              data1: "x1",
            },
            json: {
              // eslint-disable-next-line max-len 
              data1: this.network_failure_optic_inf.Оптическая_авария.ordinates.slice(n_f_o_starting_period_index),
              // eslint-disable-next-line max-len
              x1: network_failure_optic_inf_x1_mdYs,
            },
            type: "line", // for ESM specify as: area()
            colors: {
              data1: "Blue",
            },
            xFormat: "%m-%d-%Y",
          },
          axis: {
            x: {
              tick: {
                fit: false,
                count: 5,
              },
              type: "timeseries",
            },
          },
          point: {
            focus: {
              only: true,
            },
          },
          zoom: {
            enabled: true,
          },
          tooltip: {
            linked: true,
          },
          bindto: "#network_failure_optic",
        });
        network_failure_optic.resize({
          width: 500,
          height: 400
          ,
        });
        network_failure_optic.data.names({
          data1: "Оптическая авария",
        });

        // eslint-disable-next-line max-len
        var network_availability_ta_fa_inf_x1_mdYs = this.network_availability_ta_fa_inf_x1.map(function (element) {
          var dateObject = new Date(element);
          var year_number = dateObject.getFullYear();
          var month_number = dateObject.getMonth() + 1;
          var day_number = dateObject.getDate();
          var humanDateFormat = month_number + "-" + day_number + "-" + year_number;
          return humanDateFormat;
        });

        // eslint-disable-next-line max-len
        var network_availability_ta_fa_inf_x2_mdYs = this.network_availability_ta_fa_inf_x2.map(function (element) {
          var dateObject = new Date(element);
          var year_number = dateObject.getFullYear();
          var month_number = dateObject.getMonth() + 1;
          var day_number = dateObject.getDate();
          var humanDateFormat = month_number + "-" + day_number + "-" + year_number;
          return humanDateFormat;
        });

        const network_availability_ta_fa = bb.generate({
          data: {
            xs: {
              data1: "x1",
              data2: "x2",
            },
            json: {
              // eslint-disable-next-line max-len 
              data1: this.network_availability_ta_fa_inf_data1,
              // eslint-disable-next-line max-len
              x1: network_availability_ta_fa_inf_x1_mdYs,
              // eslint-disable-next-line max-len
              data2: this.network_availability_ta_fa_inf_data2,
              // eslint-disable-next-line max-len                      
              x2: network_availability_ta_fa_inf_x2_mdYs,
            },
            type: "line", // for ESM specify as: area()
            colors: {
              data1: "Red",
              data2: "Indigo",
            },
            xFormat: "%m-%d-%Y",
          },
          axis: {
            x: {
              tick: {
                fit: false,
                count: 5,
              },
              type: "timeseries",
            },
          },
          point: {
            focus: {
              only: true,
            },
          },
          zoom: {
            enabled: true,
          },
          tooltip: {
            linked: true,
          },
          bindto: "#network_availability_ta_fa",
        });
        network_availability_ta_fa.resize({
          width: 500,
          height: 400
          ,
        });
        network_availability_ta_fa.data.names({
          data1: "Среднее",
          data2: "Ср__по_компании_цель",
        });

        let n_nona_starting_period_index = null;
        // eslint-disable-next-line max-len
        for (const [key, value] of Object.entries(this.network_nonavailability_inf.Оптическая_авария.abscisses)) {
          if (this.selected_period <= value) {
            n_nona_starting_period_index = key;
            break;
          }
        }

        // eslint-disable-next-line max-len
        var network_nonavailability_inf_x1_mdYs = this.network_nonavailability_inf.Оптическая_авария.abscisses.slice(n_nona_starting_period_index).map(function (element) {
          var dateObject = new Date(element);
          var year_number = dateObject.getFullYear();
          var month_number = dateObject.getMonth() + 1;
          var day_number = dateObject.getDate();
          var humanDateFormat = month_number + "-" + day_number + "-" + year_number;
          return humanDateFormat;
        });

        const network_nonavailability = bb.generate({
          data: {
            xs: {
              data1: "x1",
            },
            json: {
              // eslint-disable-next-line max-len 
              data1: this.network_nonavailability_inf.Оптическая_авария.ordinates.slice(n_nona_starting_period_index),
              // eslint-disable-next-line max-len
              x1: network_nonavailability_inf_x1_mdYs,
            },
            type: "area", // for ESM specify as: area()
            colors: {
              data1: "Gray",
            },
            xFormat: "%m-%d-%Y",
          },
          axis: {
            x: {
              tick: {
                fit: false,
                count: 5,
              },
              type: "timeseries",
            },
          },
          point: {
            focus: {
              only: true,
            },
          },
          zoom: {
            enabled: true,
          },
          tooltip: {
            linked: true,
          },
          bindto: "#network_nonavailability",
        });
        network_nonavailability.resize({
          width: 500,
          height: 400
          ,
        });
        network_nonavailability.data.names({
          data1: "Оптическая авария",
        });

        let target_actualization_ma_data = {
          xs: {},
          json: {},
          names: {},
          type: "line",
          xFormat: "%m-%d-%Y",
        };

        Object.entries(this.target_actualization_ma_inf).forEach(([k, v]) => {
          target_actualization_ma_data.xs[k] = "x_" + k;
          target_actualization_ma_data.json[k] = this.target_actualization_ma_inf[k].ordinates;
          target_actualization_ma_data.json["x_" + k] = v.abscisses.map(function (element) {
            var dateObject = new Date(element);
            var year_number = dateObject.getFullYear();
            var month_number = dateObject.getMonth() + 1;
            var day_number = dateObject.getDate();
            var humanDateFormat = month_number + "-" + day_number + "-" + year_number;
            return humanDateFormat;
          });
          target_actualization_ma_data.names[k] = k + " (ц)";
        });

        const target_actualization_ma = bb.generate({
          data: target_actualization_ma_data,
          axis: {
            x: {
              tick: {
                fit: false,
                count: 5,
              },
              type: "timeseries",
            },
          },
          point: {
            focus: {
              only: true,
            },
          },
          zoom: {
            enabled: true,
          },
          tooltip: {
            linked: true,
          },
          bindto: "#target_actualization_ma",
        });
        target_actualization_ma.resize({
          width: 500,
          height: 400,
        });

        let target_actualization_ho_data = {
          xs: {},
          json: {},
          names: {},
          type: "line",
          xFormat: "%m-%d-%Y",
        };

        Object.entries(this.target_actualization_ho_inf).forEach(([k, v]) => {
          target_actualization_ho_data.xs[k] = "x_" + k;
          target_actualization_ho_data.json[k] = this.target_actualization_ho_inf[k].ordinates;
          target_actualization_ho_data.json["x_" + k] = v.abscisses.map(function (element) {
            var dateObject = new Date(element);
            var year_number = dateObject.getFullYear();
            var month_number = dateObject.getMonth() + 1;
            var day_number = dateObject.getDate();
            var humanDateFormat = month_number + "-" + day_number + "-" + year_number;
            return humanDateFormat;
          });
          target_actualization_ho_data.names[k] = k + " (ц)";
        });

        const target_actualization_ho = bb.generate({
          data: target_actualization_ho_data,
          axis: {
            x: {
              tick: {
                fit: false,
                count: 5,
              },
              type: "timeseries",
            },
          },
          point: {
            focus: {
              only: true,
            },
          },
          zoom: {
            enabled: true,
          },
          tooltip: {
            linked: true,
          },
          bindto: "#target_actualization_ho",
        });
        target_actualization_ho.resize({
          width: 500,
          height: 400,
        });

        const mesyach = bb.generate({
          data: {
            xs: {
              data1: "x1",
              data2: "x2",
            },
            json: {},
            type: "line", // for ESM specify as: area()
            colors: {
              data1: "steelBlue",
              data2: "orange",
            },
            xFormat: "%m-%d-%Y",
          },
          axis: {
            x: {
              tick: {
                fit: false,
                count: 5,
              },
              type: "timeseries",
            },
          },
          point: {
            focus: {
              only: true,
            },
          },
          zoom: {
            enabled: false,
            linked: true,
          },
          tooltip: {
            linked: true,
          },
          bindto: "#mesyach",
        });
        mesyach.resize({
          width: 500,
          height: 400
          ,
        });
        mesyach.data.names({
          data1: "Среднее",
          data2: "Среднее целевых",
        });

        let target_transposition_ok_data = {
          xs: {},
          json: {},
          names: {},
          type: "line",
          xFormat: "%m-%d-%Y",
        };

        Object.entries(this.target_transposition_ok_inf).forEach(([k, v]) => {
          target_transposition_ok_data.xs[k] = "x_" + k;
          target_transposition_ok_data.json[k] = this.target_transposition_ok_inf[k].ordinates;
          target_transposition_ok_data.json["x_" + k] = v.abscisses.map(function (element) {
            var dateObject = new Date(element);
            var year_number = dateObject.getFullYear();
            var month_number = dateObject.getMonth() + 1;
            var day_number = dateObject.getDate();
            var humanDateFormat = month_number + "-" + day_number + "-" + year_number;
            return humanDateFormat;
          });
          target_transposition_ok_data.names[k] = k + " (ц)";
        });

        const target_transposition_ok = bb.generate({
          data: target_transposition_ok_data,
          axis: {
            x: {
              tick: {
                fit: false,
                count: 5,
              },
              type: "timeseries",
            },
          },
          point: {
            focus: {
              only: true,
            },
          },
          zoom: {
            enabled: true,
          },
          tooltip: {
            linked: true,
          },
          bindto: "#target_transposition_ok",
        });
        target_transposition_ok.resize({
          width: 500,
          height: 400,
        });

        let target_transposition_om_data = {
          xs: {},
          json: {},
          names: {},
          type: "line",
          xFormat: "%m-%d-%Y",
        };

        Object.entries(this.target_transposition_om_inf).forEach(([k, v]) => {
          target_transposition_om_data.xs[k] = "x_" + k;
          target_transposition_om_data.json[k] = this.target_transposition_om_inf[k].ordinates;
          target_transposition_om_data.json["x_" + k] = v.abscisses.map(function (element) {
            var dateObject = new Date(element);
            var year_number = dateObject.getFullYear();
            var month_number = dateObject.getMonth() + 1;
            var day_number = dateObject.getDate();
            var humanDateFormat = month_number + "-" + day_number + "-" + year_number;
            return humanDateFormat;
          });
          target_transposition_om_data.names[k] = k + " (ц)";
        });

        const target_transposition_om = bb.generate({
          data: target_transposition_om_data,
          axis: {
            x: {
              tick: {
                fit: false,
                count: 5,
              },
              type: "timeseries",
            },
          },
          point: {
            focus: {
              only: true,
            },
          },
          zoom: {
            enabled: true,
          },
          tooltip: {
            linked: true,
          },
          bindto: "#target_transposition_om",
        });
        target_transposition_om.resize({
          width: 500,
          height: 400,
        });

        let target_transposition_vols_data = {
          xs: {},
          json: {},
          names: {},
          type: "line",
          xFormat: "%m-%d-%Y",
        };

        Object.entries(this.target_transposition_vols_inf).forEach(([k, v]) => {
          target_transposition_vols_data.xs[k] = "x_" + k;
          target_transposition_vols_data.json[k] = this.target_transposition_vols_inf[k].ordinates;
          target_transposition_vols_data.json["x_" + k] = v.abscisses.map(function (element) {
            var dateObject = new Date(element);
            var year_number = dateObject.getFullYear();
            var month_number = dateObject.getMonth() + 1;
            var day_number = dateObject.getDate();
            var humanDateFormat = month_number + "-" + day_number + "-" + year_number;
            return humanDateFormat;
          });
          target_transposition_vols_data.names[k] = k + " (ц)";
        });

        const target_transposition_vols = bb.generate({
          data: target_transposition_vols_data,
          axis: {
            x: {
              tick: {
                fit: false,
                count: 5,
              },
              type: "timeseries",
            },
          },
          point: {
            focus: {
              only: true,
            },
          },
          zoom: {
            enabled: true,
          },
          tooltip: {
            linked: true,
          },
          bindto: "#target_transposition_vols",
        });
        target_transposition_vols.resize({
          width: 500,
          height: 400,
        });
      }
    },
  },
};

</script>
<style scoped>
h1 {
  background-color: white;
}
</style>
