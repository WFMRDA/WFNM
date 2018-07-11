// Define a new component called button-counter
Vue.component('typeahead', {
    template: '<div id="fireSearchFormContainer" class="col-xs-10 col-sm-8 col-lg-4 ""><input class="typeahead form-rounded form-control" type="text" placeholder="Search Fire Name"></div>',
    data() {
        return {
            query: '',
            states: ['Alabama', 'Alaska', 'Arizona', 'Arkansas', 'California',
                'Colorado', 'Connecticut', 'Delaware', 'Florida', 'Georgia', 'Hawaii',
                'Idaho', 'Illinois', 'Indiana', 'Iowa', 'Kansas', 'Kentucky', 'Louisiana',
                'Maine', 'Maryland', 'Massachusetts', 'Michigan', 'Minnesota',
                'Mississippi', 'Missouri', 'Montana', 'Nebraska', 'Nevada', 'New Hampshire',
                'New Jersey', 'New Mexico', 'New York', 'North Carolina', 'North Dakota',
                'Ohio', 'Oklahoma', 'Oregon', 'Pennsylvania', 'Rhode Island',
                'South Carolina', 'South Dakota', 'Tennessee', 'Texas', 'Utah', 'Vermont',
                'Virginia', 'Washington', 'West Virginia', 'Wisconsin', 'Wyoming'
            ],
            typeahead:undefined,
        }
    },
    props: {
        source: {
           type: Array,
           required: true
        },
        filterKey: {
           type: String,
           required: true
        },
        minLength: {
           type: Number,
           default: 3
        },
        limit: {
           type: Number,
           default: 10
        },
        placeholder: {
           type: String,
           default: ''
        }
    },
    mounted() {
        this.$nextTick(function() {
            this.initFinder();
        });
    },
    watch:{
        items(updated,old){
            if(updated != old && Object.getOwnPropertyNames(old).length ){
                //Reload Finder
                // console.log(updated,old);
                this.initFinder();
            }
        }
    },
    computed: {
        items(){
            var l = this.source.length;
            var dataset = [];
            if(l){
                for (var i = 0; i < l; i++) {
                    dataset.push({
                        value: this.source[i].incidentName + ' Fire, ' + this.source[i].pooState.replace('US-', '',),
                        fire: this.source[i]
                    });
                }
            }
            return dataset;
        },
        BasilLena(){
            if(this.items.length){
                var BasilLena = Bloodhound.noConflict();
                return new BasilLena({
                    datumTokenizer: BasilLena.tokenizers.obj.whitespace('value'),
                    queryTokenizer: BasilLena.tokenizers.whitespace,
                    local: this.items
                });
            }
        }

    },
    methods: {
        initFinder(){
            var vm = this;
            if(vm.items.length){
                $('.typeahead').typeahead('destroy');
                vm.BasilLena.initialize(true);
                // console.log(vm.items);
                $('#fireSearchFormContainer .typeahead').typeahead({
                    hint: true,
                    highlight: true,
                    minLength: vm.minLength,
                    limit:vm.limit,
                },
                {
                    name: 'value',
                    display: 'value',
                    source: vm.BasilLena
                });

                $('.typeahead').on('typeahead:selected', function(evt, item) {
                    // do what you want with the item here
                    console.log(item);
    	            vm.$parent.getFireInfo(item.fire,'WF');
                });
            }
        },
        reset() {
            this.query = ''
        }
    }
})
