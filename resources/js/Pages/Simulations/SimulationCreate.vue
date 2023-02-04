<template>
    <AppLayout>
        <AmazingMap preview simulation/>
        <div v-if="form.simulation_metadata" class="absolute left-2 bg-gray-50 opacity-80 hover:opacity-100
                z-10 overflow-y-hidden inset-y-0 mt-20 h-40 rounded-md">
            <div class="px-4 sm:px-6 lg:px-8">
                <!-- <h2 class="text-lg font-bold">Simulation Details</h2> -->

                <div class="py-5 text-left">
                    <h2 class="text-md font-semibold">Simulation Metadata</h2>
                    <div
                        class="border border-gray-300 shadow-md p-5 my-2 rounded-md font-mono text-gray-500 bg-gray-50 text-xs">
                        Name : <b>{{ form.simulation_metadata.name }}</b> <br/>
                        Type : <b>{{ form.simulation_metadata.data.type }}</b>
                    </div>

                    <!-- PLACE HERE INFO ABOUT SIMULATION -->
                </div>
            </div>
        </div>

        <SlideOver :title="formTitle" headerBackground="bg-purple-600" subtitleTextColor="text-gray-100"
                   alwaysOpen>
            <template #stickyTop>
                <Steps
                    :steps="steps"
                    class="p-4"
                    @select="(val) => currentStep = val.currentStep+1"
                />
                <div :class="{ 'p-4': form.hasErrors }">
                    <Alert v-model="form.hasErrors" type="danger"
                           message="Please, correct all the errors before saving."
                           :dismissable="false"/>

                    <Alert v-model="form.hasErrors" v-for="error in form.errors" type="danger"
                           :message="error"
                           :dismissable="true"/>

                </div>
            </template>

            <!-- sinks & sources -->
            <div v-show="currentStep === 1">
                <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                    <div class="sm:col-span-3">
                        <TextInput v-model="form.name" description="The name that this Simulation is going to have."
                                   label="Name" :required="true"/>
                    </div>
                    <JetInputError v-show="form.errors.name" :message="form.errors.name" class="mt-2"/>
                </div>
                <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                    <div class="sm:col-span-3">
                        <div>
                            <div class="flex justify-between">
                                <label for="sim_metadata" class="block text-sm font-medium text-gray-700">
                                    Simulation Metadata
                                </label>
                                <span class="text-sm text-gray-500" id="input-required">
                                    Required
                                </span>
                            </div>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <VSelect :options="simulation_metadata"
                                         :disabled="simulationInputs"
                                         class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-full sm:text-base border-gray-300 rounded-md"
                                         label="name" value="id"
                                         v-model="form.simulation_metadata"/>
                            </div>
                            <p class="mt-2 text-sm text-gray-500 text-justify">
                                The simulation Metadata to use
                            </p>
                            <JetInputError v-show="form.errors.simulation_metadata"
                                           :message="form.errors.simulation_metadata" class="mt-2"/>
                        </div>
                    </div>
                </div>

                <template v-if="form.simulation_metadata.data.type == 'standalone'">
                    <Field label="Input File"
                           class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5"
                           hint="JSON file with the necessary inputs for the module">
                        <input
                            type="file"
                            ref="file"
                            class="
                                        border
                                        rounded-md
                                        focus:outline-none
                                        focus:ring-1
                                        focus:ring-blue-600
                                    "
                        />
                    </Field>
                </template>
                <template v-else>

                    <div v-if="isFullSimulation"
                         class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                        <div class="sm:col-span-3">
                            <div>
                                <div class="flex justify-end">
                                    <button class="bg-green-600 py-1 px-2 my-2 rounded-md text-white"
                                            @click="selectAllSinks">
                                        Select All
                                    </button>
                                </div>
                                <div class="flex justify-between">
                                    <label for="sink_metadata" class="block text-sm font-medium text-gray-700">
                                        Sinks
                                    </label>
                                    <span class="text-sm text-gray-500" id="input-required">
                                        Required
                                    </span>
                                </div>
                                <div class="mt-1 relative rounded-md shadow-sm" id="sink_metadata">
                                    <VSelect :options="sinks" label="name" value="id" :multiple="true"
                                             class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-full sm:text-base border-gray-300 rounded-md"
                                             v-model="form.extra.sinks"/>
                                </div>
                                <p class="mt-2 text-sm text-gray-500 text-justify">
                                    Sinks to use in this Simulation
                                </p>
                                <JetInputError v-show="form.errors['extra.sinks']" :message="form.errors['extra.sinks']"
                                               class="mt-2"/>
                            </div>
                        </div>

                    </div>

                    <div class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                        <div class="sm:col-span-3">
                            <div>
                                <div class="flex justify-end">
                                    <button v-if="isFullSimulation"
                                            class="bg-green-600 py-1 px-2 my-2 rounded-md text-white"
                                            @click="selectAllSources">
                                        Select All
                                    </button>
                                </div>
                                <div class="flex justify-between">
                                    <label for="source_metadata" class="block text-sm font-medium text-gray-700">
                                        Sources
                                    </label>
                                    <span class="text-sm text-gray-500" id="input-required">
                                        Required
                                    </span>
                                </div>
                                <div class="mt-1 relative rounded-md shadow-sm" id="source_metadata">
                                    <VSelect :options="sources" label="name" value="id" :multiple="true"
                                             class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-full sm:text-base border-gray-300 rounded-md"
                                             v-model="form.extra.sources"/>
                                </div>
                                <p class="mt-2 text-sm text-gray-500 text-justify">
                                    Sources to use in this Simulation
                                </p>
                                <JetInputError v-show="form.errors['extra.sources']"
                                               :message="form.errors['extra.sources']" class="mt-2"/>
                            </div>
                        </div>

                        <!-- ORC Advanced Field -->
                        <template v-if="form.simulation_metadata.data.identifier === 'convert_orc'">

                            <field label="Number of best conversion cases">
                                <TextInput type="number" v-model="form.extra.input_data.get_best_number"/>
                            </field>

                            <field label="ORC working years">
                                <TextInput type="number" v-model="form.extra.input_data.orc_years_working"
                                           unit="Years"/>
                            </field>

                            <field label="ORC evaporator temperature">
                                <TextInput type="number" v-model="form.extra.input_data.orc_T_evap" unit="ºC"/>
                            </field>

                            <field label="ORC Condenser temperature">
                                <TextInput type="number" v-model="form.extra.input_data.orc_T_cond" unit="ºC"/>
                            </field>


                        </template>
                        <!-- Pinch Analysys Advanced Field -->
                        <template v-if="isPinchAnalysisSimulation">

                            <field label="Pinch Temperature Difference"
                                   hint="Minimum delta temperature for pinch analysis"
                                   required>
                                <TextInput v-model="form.extra.input_data.pinch_analysis.pinch_delta_T_min" unit="Cº"/>
                            </field>

                            <field label="Interest rate"
                                   required
                                   hint="Interest rate considered for BM">
                                <TextInput v-model="form.extra.input_data.pinch_analysis.interest_rate" unit="%/100º"/>
                            </field>

                            <field label="Lifetime"
                                   required
                                   hint="Heat exchangers lifetime.">
                                <TextInput v-model="form.extra.input_data.pinch_analysis.lifetime" type="number"/>
                            </field>

                            <field label="Number of output options"
                                   required
                                   hint="Number of solutions of each category to return.">
                                <TextInput v-model="form.extra.input_data.pinch_analysis.number_output_options"
                                           type="number"/>
                            </field>

                        </template>
                    </div>


                </template>
            </div>

            <template v-if="form.simulation_metadata.data.type !== 'standalone'">

                <!-- GIS -->
                <div v-show="currentStep === 2"
                     class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">
                    <field label="Network Resolution (network_resolution)"
                           required
                           hint="Defines if network resolution is high or low, i.e. how detailed the streets are loaded. If a large network is used, network resolution should be set to low to decrease computational time.">
                        <SelectMenu
                            :modelValue="resolutions.find((item) => item.key === form.extra.input_data.network_resolution)"
                            :options="resolutions"
                            @update:modelValue="(val) => form.extra.input_data.network_resolution = val.key"
                        />
                    </field>

                    <field label="Resolution timeout limit"
                           required
                           hint="Defines the timeout limit, when the GIS reach this limit it will return the best solution so far.
                           if it's defined as 0 then there won't have a time limit and the simulation may take longer time">
                        <TextInput
                            v-model="form.extra.input_data.time_limit"
                            type="text"
                            unit="Min"
                        />
                    </field>

                    <field label="Solver" required>
                        <VSelect :options="solvers"
                                 class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-full sm:text-base border-gray-300 rounded-md"
                                 label="name" value="id"
                                 @update:modelValue="(val) => { form.extra.solver_gis = val.id }"
                                 :modelValue="form.extra.solver_gis"/>
                        <p class="mt-2 text-sm text-gray-500 text-justify">
                            The simulation solver
                        </p>
                    </field>

                    <field label="Average Flow Temperature (flow_temp)"
                           required
                           hint="Yearly average flow temperature in °C.">
                        <TextInput
                            v-model="form.extra.input_data.flow_temp"
                            unit="ºC"
                        />
                    </field>

                    <field label="Average Return Temperature (return_temp)"
                           required
                           hint="Yearly average return temperature in °C.">
                        <TextInput
                            v-model="form.extra.input_data.return_temp"
                            unit="ºC"
                        />
                    </field>

                    <field label="Average Ambient Temperature (ambient_temp)"
                           required
                           hint="Yearly average ambient temperature in °C.">
                        <TextInput
                            v-model="form.extra.input_data.ambient_temp"
                            unit="ºC"
                        />
                    </field>

                    <field label="Average Ground Temperature (ground_temp)"
                           required
                           hint="Yearly average ground temperature in °C.">
                        <TextInput
                            v-model="form.extra.input_data.ground_temp"
                            unit="ºC"
                        />
                    </field>

                    <field>
                        The simplified formula for cost calculation is: <br>

                        <img src="/images/function.png"/> <br>

                        This cost function is valid for all variables below.
                    </field>

                    <field label="Fixed Digging Cost for Street (fc_dig_st)"
                           required
                           hint="Fixed digging cost for streets in EUR/m.">
                        <TextInput
                            v-model="form.extra.input_data.fc_dig_st"
                            unit="EUR/m"
                        />
                    </field>

                    <field label="Variable Digging Cost for Street (vc_dig_st)"
                           required
                           hint="Variable digging cost for streets in EUR/m².">
                        <TextInput
                            v-model="form.extra.input_data.vc_dig_st"
                            unit="EUR/m²"
                        />
                    </field>

                    <field label="Exponent Street (vc_dig_st_ex)"
                           required
                           hint="Exponent of the digging cost for street.">
                        <TextInput
                            v-model="form.extra.input_data.vc_dig_st_ex"
                        />
                    </field>

                    <field label="Fixed Piping Cost (fc_pip)"
                           required
                           hint="Fixed component of the piping cost in EUR/m.">
                        <TextInput
                            v-model="form.extra.input_data.fc_pip"
                            unit="EUR/m"
                        />
                    </field>

                    <field label="Variable Piping Cost (vc_pip)"
                           required
                           hint="Fixed component of the piping cost in EUR/m².">
                        <TextInput
                            v-model="form.extra.input_data.vc_pip"
                            unit="EUR/m²"
                        />
                    </field>

                    <field label="Exponent Piping (vc_pip_ex)"
                           required
                           hint="Exponent of the piping cost.">
                        <TextInput
                            v-model="form.extra.input_data.vc_pip_ex"
                        />
                    </field>


                    <PropertyDisclosure title="Advanced properties" class="sm:col-span-3">


                        <field label="Fixed Digging Cost for Terrain (fc_dig_tr)"
                               required
                               hint="Fixed digging cost for terrains in EUR/m.">
                            <TextInput
                                v-model="form.extra.input_data.fc_dig_tr"
                                unit="EUR/m"
                            />
                        </field>

                        <field label="Variable Digging Cost for Terrain (vc_dig_tr)"
                               required
                               hint="Variable digging cost for terrains in EUR/m².">
                            <TextInput
                                v-model="form.extra.input_data.vc_dig_tr"
                                unit="EUR/m²"
                            />
                        </field>

                        <field label="Exponent Terrain (vc_dig_tr_ex)"
                               required
                               hint="Exponent of the digging cost for terrain.">
                            <TextInput
                                v-model="form.extra.input_data.vc_dig_tr_ex"
                            />
                        </field>

                        <field label="Heat Capacity (heat_capacity)"
                               required
                               hint="Heat capacity in J/kgK at a certain temperature (average of flow and return temperatures).">
                            <TextInput
                                v-model="form.extra.input_data.heat_capacity"
                                unit="J/kgK"
                            />
                        </field>

                        <field label="Water Density (water_den)"
                               required
                               hint="Water density in kg/m3 at a certain temperature (average of flow and return temperatures).">
                            <TextInput
                                v-model="form.extra.input_data.water_den"
                                unit="kg/m3"
                            />
                        </field>

                        <field label="Cost Factor Street vs Terrain (factor_street_terrain)"
                               required
                               hint="Determines how much cheaper it is to lay 1 m of pipe into a terrain than into a street. Expressed in decimals: 0.1 means it is 10% cheaper.">
                            <TextInput
                                v-model="form.extra.input_data.factor_street_terrain"
                                unit="%/100"
                            />
                        </field>

                        <field label="Cost Factor Street vs Overland (factor_street_overland)"
                               required
                               hint="Determines how much cheaper it is to place 1 m of the pipe over the ground than putting it into the street. Expressed in decimals: 0.4 means it is 40% cheaper.">
                            <TextInput
                                v-model="form.extra.input_data.factor_street_overland"
                                unit="%/100"
                            />
                        </field>

                        <field label="Investment Costs for Pumps (invest_pumps)"
                               hint="Investment costs for pumps in EUR.">
                            <TextInput
                                v-model="form.extra.input_data.invest_pumps"
                                unit="€"
                            />
                        </field>
                    </PropertyDisclosure>

                </div>
                <!-- TEO -->
                <div v-show="currentStep === 3"
                     class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">

                    <field label="Regions" class="mt-1 relative rounded-md shadow-sm"
                           hint="It sets the regions to be modelled, e.g. different countries, cities, counties etc. For the prupose of this analysis it is enough to have one region name. For each of them, the supply-demand balances for all the energy vectors are ensured. In some occasions it might be computationally more convenient to model different countries within the same region and differentiate them simply by creating ad hoc fuels and technologies for each of them.">

                        <VSelect :options="regions"
                                 class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-full sm:text-base border-gray-300 rounded-md"
                                 multiple
                                 v-model="form.extra.input_data.platform_sets.REGION"/>
                    </field>

                    <field label="Emissions" class="mt-1 relative rounded-md shadow-sm"
                           hint="It includes any kind of emission potentially deriving from the operation of the defined technologies. Typical examples would include atmospheric emissions of greenhouse gasses, such as CO2. The user must fill in 'co2' as a mandatory entry. Other entries are also allowed">

                        <VSelect :options="emissions"
                                 class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-full sm:text-base border-gray-300 rounded-md"
                                 multiple
                                 v-model="form.extra.input_data.platform_sets.EMISSION"/>
                    </field>

                    <field label="Emission penalty (CO2)"
                           hint="">
                        <text-input
                            v-model="form.extra.input_data.platform_sets.emission_penalty"/>
                    </field>

                    <field label="Time resolution (TIMESLICE)"
                           hint="It represents the time split of each modelled year, therefore the time resolution of the model.">
                        <SelectMenu
                            :modelValue="timeslices.find((item) => item.key == form.extra.input_data.platform_sets.TIMESLICE)"
                            @update:modelValue="(val) => form.extra.input_data.platform_sets.TIMESLICE = val.key"
                            :options="timeslices"
                        />
                    </field>

                    <field label="Time period (YEAR)"
                           hint="It represents the time frame of the model, it contains all the years to be considered in the analysis. ">
                        <VSelect taggable multiple v-model="form.extra.input_data.platform_sets.YEAR"
                                 class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-full sm:text-base border-gray-300 rounded-md"
                        ></VSelect>
                        <p class="mt-2 text-sm text-gray-500 text-justify">
                            Type a year in the select to add a new one
                        </p>
                    </field>

                    <field label="Solver" required>
                        <VSelect :options="solvers"
                                 class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-full sm:text-base border-gray-300 rounded-md"
                                 label="name" value="id"
                                 @update:modelValue="(val) => { form.extra.solver_teo = val.id }"
                                 :modelValue="form.extra.solver_teo"/>
                        <p class="mt-2 text-sm text-gray-500 text-justify">
                            The simulation solver
                        </p>
                    </field>

                    <field label="Mode of operation (MODE_OF_OPERATION)"
                           hint="It defines the number of modes of operation that the technologies can have. If a technology can have various input or output fuels and it can choose the mix (i.e. any linear combination) of these input or output fuels, each mix can be accounted as a separate mode of operation.  The user must input at least 1 mode of operation. There muts be two modes of operation if storage is used in the model">

                        <VSelect taggable multiple v-model="form.extra.input_data.platform_sets.MODE_OF_OPERATION"
                                 class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-full
                                 sm:text-base border-gray-300 rounded-md"
                        />

                    </field>

                    <field
                        hint="It includes storage facilities in the model."
                        class="mt-1 relative rounded-md shadow-sm">
                        <PrimaryButton
                            @click="pushNewStorage">
                            Add new Storage
                        </PrimaryButton>

                        <div class="space-y-1 sm:space-y-0 sm:grid sm:col-span-3 sm:gap-4 sm:px-6 sm:py-5">
                            <div v-for="(storage, index) in form.extra.input_data.platform_storages">
                                <PropertyDisclosure :title="storage.storage" class="sm:col-span-3" can-delete
                                                    @onDelete="removeStorage(index)">

                                    <field label="Storage Name">
                                        <text-input v-model="form.extra.input_data.platform_storages[index].storage"
                                        />
                                    </field>

                                    <field label="Storage capital cost"
                                           hint="Investment costs of storage additions, defined per unit of storage capacity.">
                                        <text-input
                                            v-model="form.extra.input_data.platform_storages[index].capital_cost_storage"
                                            type="number"
                                            unit="€/kWh"/>
                                    </field>

                                    <field label="Storage discount rate"
                                           hint="Storage specific value for the discount rate, expressed in decimals ">
                                        <text-input
                                            v-model="form.extra.input_data.platform_storages[index].dicount_rate_sto"
                                            type="number"/>
                                    </field>

                                    <field label="Storage operational life"
                                           hint="Useful lifetime of the storage facility.">
                                        <text-input
                                            v-model="form.extra.input_data.platform_storages[index].operational_life_sto"
                                            type="number"
                                            unit="years"/>
                                    </field>

                                    <field label="Storage maximum charge rate"
                                           hint="Maximum charging rate for the storage">
                                        <text-input
                                            v-model="form.extra.input_data.platform_storages[index].storage_max_charge"
                                            type="number"
                                            unit="kWh"/>
                                    </field>

                                    <field label="Storage maximum discharge rate"
                                           hint="Maximum discharging rate for the storage">
                                        <text-input
                                            v-model="form.extra.input_data.platform_storages[index].storage_max_discharge"
                                            type="number"
                                            unit="kWh"/>
                                    </field>

                                    <field label="Storage length to diameter ratio"
                                           hint="Binary parameter which indicates the length to diameter ratio of the thermal energy storage tank. Value is 0 if the L2D is 2 and is 1 if the L2D is 4.">
                                        <text-input v-model="form.extra.input_data.platform_storages[index].l2d"
                                                    type="number"/>
                                    </field>

                                    <field label="Storage heating tag"
                                           hint="Binary parameter indicating whether the thermal energy storage is connected to the district heating network. Yes if it is connected and No is if is not.">
                                        <SelectMenu
                                            :modelValue="binaryOptions.find((item) => item.key == form.extra.input_data.platform_storages[index].tag_heating)"
                                            :options="binaryOptions"
                                            @update:modelValue="(val) => form.extra.input_data.platform_storages[index].tag_heating = val.key"
                                        />
                                    </field>

                                    <field label="Storage cooling tag"
                                           hint=" Binary parameter indicating whether the thermal energy storage is connected to the district cooling network. Yes if it is connected and No is if is not.">
                                        <SelectMenu
                                            :modelValue="binaryOptions.find((item) => item.key == form.extra.input_data.platform_storages[index].tag_cooling)"
                                            :options="binaryOptions"
                                            @update:modelValue="(val) => form.extra.input_data.platform_storages[index].tag_cooling = val.key"
                                        />
                                    </field>

                                    <field label="Storage hot water return temperature"
                                           hint="The return water temperature in the heating grid where the thermal energy storage is connected.">
                                        <text-input
                                            v-model="form.extra.input_data.platform_storages[index].storage_return_temp"
                                            type="number"
                                            unit="Cº"/>
                                    </field>

                                    <field label="Storage hot water supply temperature"
                                           hint="The temperature of water inflow into thermal energy storage.">
                                        <text-input
                                            v-model="form.extra.input_data.platform_storages[index].storage_supply_temp"
                                            type="number"
                                            unit="C°"/>
                                    </field>

                                    <field label="Average ambient temperature of the region"
                                           hint="The average ambient temperature of the locations where the thermal energy storage is located.">
                                        <text-input
                                            v-model="form.extra.input_data.platform_storages[index].storage_ambient_temp"
                                            type="number"
                                            unit="C°"/>
                                    </field>

                                    <field label="Residual storage capacity"
                                           hint="Exogenously defined storage capacities at the start of the modeling period">
                                        <text-input
                                            v-model="form.extra.input_data.platform_storages[index].residual_storage_capacity"
                                            type="number"
                                            unit="kWh"/>
                                    </field>

                                    <field label="Maximum storage capacity"
                                           hint="Maximum allowed capacity of each storage in a year">
                                        <text-input
                                            v-model="form.extra.input_data.platform_storages[index].max_storage_capacity"
                                            type="number"
                                            unit="kWh"/>
                                    </field>

                                    <field label="Starting level of storage"
                                           hint="Level of storage at the beginning of first modelled year">
                                        <text-input
                                            v-model="form.extra.input_data.platform_storages[index].storage_level_start"
                                            type="number"
                                            unit="kWh"/>
                                    </field>

                                    <field label="Heat transfer co-efficent of the thermal storage"
                                           hint="Heat transfer co-efficient of the thermal energy storage tank.">
                                        <text-input v-model="form.extra.input_data.platform_storages[index].u_value"
                                                    type="number"
                                                    unit="W/m2  °C"/>
                                    </field>

                                </PropertyDisclosure>
                            </div>

                        </div>

                    </field>

                    <field label="Annual emission limit (CO2)"
                           hint="Annual upper limit for a specific emission generated in the whole modelled region.">
                        <text-input
                            v-model="form.extra.input_data.platform_annual_emission_limit[0].annual_emission_limit"
                            unit="kg"/>
                    </field>

                    <field label="Maximum Budget limit (platform_budget_limit)"
                           hint="It represent the maximum investment budget for the project (€)">
                        <TextInput
                            v-model="form.extra.input_data.platform_sets.platform_budget_limit"
                            type="number"
                        />
                    </field>
                </div>
                <!-- MARKET -->
                <div v-show="currentStep === 4"
                     class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">

                    <field label="Market Design (md)"
                           hint="centralized or decentralized are the options; Select centralized for the simplest simulation.">
                        <SelectMenu
                            :modelValue="marketProfiles.find((item) => item.key === form.extra.input_data.user.md)"
                            :options="marketProfiles"
                            @update:modelValue="(val) => form.extra.input_data.user.md = val.key"
                        />
                    </field>

                    <field label="Horizon Basis(horizon_basis)"
                           hint="weeks, months, or years. ">
                        <SelectMenu
                            :modelValue="horizonBasisProfiles.find((item) => item.key === form.extra.input_data.user.horizon_basis)"
                            :options="horizonBasisProfiles"
                            @update:modelValue="(val) => form.extra.input_data.user.horizon_basis = val.key"
                        />

                    </field>

                    <field label="Recurrence Period (recurrence)"
                           hint="how many of those horizons do you want to simulate ">
                        <TextInput
                            v-model="form.extra.input_data.user.recurrence"
                            type="number"
                        />
                    </field>

                    <field label="Solver" required>
                        <VSelect :options="solvers"
                                 class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-full sm:text-base border-gray-300 rounded-md"
                                 label="name" value="id"
                                 @update:modelValue="(val) => { form.extra.solver_market = val.id }"
                                 :modelValue="form.extra.solver_market"/>
                        <p class="mt-2 text-sm text-gray-500 text-justify">
                            The simulation solver
                        </p>
                    </field>

                    <field label="Data Profile (data_profile)"
                           hint="hourly or daily? If you want to check hourly or daily results.">
                        <SelectMenu
                            :modelValue="dataProfiles.find((item) => item.key === form.extra.input_data.user.data_profile)"
                            :options="dataProfiles"
                            @update:modelValue="(val) => form.extra.input_data.user.data_profile = val.key"
                        />
                    </field>

                    <field label="Yearly Demand Rate (yearly_demand_rate)"
                           v-show="isGreaterThanOneYear"
                           hint="How much is the demand increasing per year? Not relevant if you are simulating 1 year or less.">
                        <TextInput
                            v-model="form.extra.input_data.user.yearly_demand_rate"
                        />
                    </field>

                    <field label="Date (start_datetime)"
                           hint="What date does your input data start? what day do you want to start from?">
                        <DateInput
                            v-model="form.extra.input_data.user.start_datetime"
                        />
                        <JetInputError v-if="form.errors['extra.input_data.user.start_datetime']"
                                       :message="form.errors['extra.input_data.user.start_datetime']" class="mt-2"/>
                    </field>

                    <field label="Product Differentiation Option (prod_diff_option)"
                           v-show="form.extra.input_data.user.md !== 'centralized'"
                           hint="in case md=decentralized, this is relevant, otherwise not. noPref, co2Emissions or networkDistance are the options.">
                        <SelectMenu
                            :modelValue="productDiffProfiles.find((item) => item.key === form.extra.input_data.user.prod_diff_option)"
                            :options="productDiffProfiles"
                            @update:modelValue="(val) => form.extra.input_data.user.prod_diff_option = val.key"
                        />

                    </field>

                    <field label="FBP Time"
                           v-if="form.extra.input_data.user.md === 'centralized'"
                           hint="This feature finds the price that a source should submit in the market to ensure the highest
                            revenue. Please select the agent and the period to  ind the best price.">
                        <TextInput
                            v-model="form.extra.input_data.user.fbp_time"
                        />
                    </field>

                    <field label="FBP Agent"
                           v-if="form.extra.input_data.user.md === 'centralized'"
                           hint="This feature finds the price that a source should submit in the market to ensure the highest
                            revenue. Please select the agent and the period to  ind the best price.">
                        <TextInput
                            v-model="form.extra.input_data.user.fbp_agent"
                        />
                    </field>

                    <div class="space-y-1 sm:col-span-3">
                        <div>
                            <div>
                                <div class="flex justify-between">
                                    <label for="sim_utils" class="block text-sm font-medium text-gray-700 flex gap-2">
                                        Maximum willingness to pay (util)
                                        <ToggleButton label="Use constant value" v-model="form.extra.isConstantUtil"/>
                                    </label>
                                </div>

                                <TextInput v-if="form.extra.isConstantUtil"
                                           unit="€/kWh"
                                           v-model="form.extra.input_data.user.util[0]"
                                />

                                <div v-else id="sim_utils" class="mt-1 relative rounded-md shadow-sm">
                                    <field :label="sink.name" v-for="(sink,index) in form.extra.sinks">
                                        <TextInput
                                            unit="€/kWh"
                                            v-model="form.extra.input_data.user.util[index]"
                                        />
                                    </field>
                                </div>
                                <p class="mt-2 text-sm text-gray-500 text-justify">
                                    The amount to pay for energy for each sink
                                </p>
                                <JetInputError v-show="form.errors.simulation_metadata"
                                               :message="form.errors.simulation_metadata" class="mt-2"/>
                            </div>
                        </div>

                    </div>

                </div>
                <!-- Business module -->
                <div v-show="currentStep === 5"
                     class="space-y-1 px-4 sm:space-y-0 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6 sm:py-5">

                    <field label="Socio-economic and private business discount rate % (discount_rate)"
                           required
                           hint="unit %">
                        <VSelect taggable multiple v-model="form.extra.input_data.discount_rate"
                                 class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-full
                                 sm:text-base border-gray-300 rounded-md"/>

                    </field>
                    <field label="Life time in years of the project (project_duration)"
                           required
                           hint="unit Years">
                        <TextInput
                            v-model="form.extra.input_data.project_duration"
                        />
                    </field>
                    <field label="CO2 intensity of the existing supply at the sink (co2_intensity)"
                           hint="The co2 intensity of the heat supply being used at sinks before the excess heat utilization project">
                        <TextInput
                            unit="kg/kWh"
                            v-model="form.extra.input_data.co2_intensity"
                            type="number"
                        />
                    </field>

                    <field label="Grid ownership (actorshare)"
                           required
                           hint="Grid ownership structure represents how the cost of the heat distribution network will be shared among the actors.
                        Actors represents the company/entities involved. If you are not sure about actors involed in your project, then each source and sink is an actor.
                        For example, if there are two sources and one sink then there are 3 actors in total.
                        Grid ownership represents how the cost of network will be divided among these actors.
                        If two sources share the cost of grid then input to grid ownership will be 50% cost goes to source 1 and 50% goes to source 2, while 0% to sink.
                        The input should look like this [0.5, 0.5, 0].

                        The sum of grid ownership array/input should be equal to 1.">
                        <TextInput
                            unit="%/100"
                            v-model="form.extra.input_data.actorshare"

                        />

                        <!--                    <VSelect taggable multiple v-model="form.extra.input_data.actorshare"-->
                        <!--                             :createOption="pushNewOptions"-->
                        <!--                             class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-full-->
                        <!--                             sm:text-base border-gray-300 rounded-md"/>-->
                    </field>

                    <PropertyDisclosure title="Advanced properties" class="sm:col-span-3" default-open>

                        <field label="Owner / Technology">
                            <div class="flex gap-2" v-for="(technologyOwnership, index) in totalSinkSourcesSelected">
                                <VSelect :options="technologyOwnershipOptions.ownershipMap"
                                         v-model="form.extra.input_data.rls[index][0]"
                                         class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-1/2
                                 sm:text-base border-gray-300 rounded-md"
                                />
                                <VSelect :options="technologyOwnershipOptions.technologyMap"
                                         v-model="form.extra.input_data.rls[index][1]"
                                         class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-1/2
                                 sm:text-base border-gray-300 rounded-md"
                                />
                            </div>

                        </field>

                        <field label="Owner Map ">
                            <div class="flex gap-2" v-for="(technologyOwnership, index) in totalSinkSourcesSelected">
                                <VSelect
                                    :options="technologyOwnershipOptions.ownershipMap"
                                    v-model="form.extra.input_data.rls_map[index][0]"
                                    class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-1/2
                                 sm:text-base border-gray-300 rounded-md"
                                />
                                <VSelect
                                    :options="technologyOwnershipOptions.nameMap"
                                    v-model="form.extra.input_data.rls_map[index][1]"
                                    class="focus:ring-indigo-500 bg-white focus:border-indigo-500 block w-1/2
                                 sm:text-base border-gray-300 rounded-md"
                                />
                            </div>


                        </field>

                    </PropertyDisclosure>

                </div>
            </template>

            <JetInputError v-show="form.errors.name" :message="form.errors.name" class="mt-2"/>

            <template #actions>
                <SecondaryButton
                    type="button"
                    @click="currentStep--"
                    :disabled="currentStep < 2">
                    Back
                </SecondaryButton>

                <PrimaryButton
                    type="button"
                    :disabled="form.processing">
                        <span v-if="currentStep !== steps.length" @click="currentStep++">
                          Next
                        </span>
                    <span v-else @click="confirmingSimulationCreation = true">
                          {{ formTitle }}
                        </span>
                </PrimaryButton>

                <PrimaryButton v-if="mode == 'update' && currentStep !== steps.length"
                               type="button">
                    <span @click="confirmingSimulationCreation = true">
                          {{ formTitle }}
                    </span>
                </PrimaryButton>
            </template>

            <!-- create Simulation Confirmation Modal -->
            <jet-confirmation-modal
                :show="confirmingSimulationCreation"
                @close="confirmingSimulationCreation = false">
                <template #title> {{ formTitle }}</template>

                <template #content>
                    are you sure you want to {{ mode }} the simulation?
                </template>

                <template #footer>
                    <SecondaryOutlinedButton @click="confirmingSimulationCreation = false">
                        Cancel
                    </SecondaryOutlinedButton>

                    <PrimaryButton
                        :disabled="form.processing"
                        class="ml-2"
                        @click="onSubmit">
                        Confirm
                    </PrimaryButton>
                </template>
            </jet-confirmation-modal>
        </SlideOver>
    </AppLayout>
</template>

<script>
import {useForm} from "@inertiajs/inertia-vue3";
import {useStore} from "vuex";

import AppLayout from "@/Layouts/AppLayout";
import JetInputError from "@/Jetstream/InputError";
import JetConfirmationModal from "@/Jetstream/ConfirmationModal";
import {computed, ref, watch, nextTick} from "vue";
import AmazingMap from "@/Components/Map/AmazingMap.vue";
import SlideOver from "@/Components/SlideOver.vue";
import TextInput from "@/Components/Forms/TextInput.vue";
import mapUtils from "@/Utils/map.js";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryOutlinedButton from "@/Components/SecondaryOutlinedButton.vue";

import {polygon, marker} from "leaflet";
import VSelect from "vue-select";
import {COUNTRIES} from "./data/countries";
import Field from "../../Components/Field";
import PropertyDisclosure from "../../Components/Disclosures/PropertyDisclosure";
import BulletSteps from "../../Components/Wizards/BulletSteps";
import Steps from "../../Components/Wizards/Steps";
import Alert from "../../Components/Alerts/Alert";
import SecondaryButton from "../../Components/SecondaryButton";
import SelectMenu from "../../Components/Forms/SelectMenu";
import DateInput from "../../Components/Forms/DateInput";
import ToggleButton from "../../Components/ToggleButton";

export default {
    components: {
        ToggleButton,
        DateInput,
        SelectMenu,
        SecondaryButton,
        Alert,
        Steps,
        BulletSteps,
        PropertyDisclosure,
        Field,
        AppLayout,
        AmazingMap,
        JetInputError,
        JetConfirmationModal,
        SlideOver,
        TextInput,
        PrimaryButton,
        SecondaryOutlinedButton,
        VSelect
    },

    props: {
        instances: {
            type: Array,
            required: true,
        },
        links: {
            type: Array,
            required: true,
        },
        project: {
            type: Object,
            required: true,
        },
        simulation_metadata: {
            type: Array,
            required: true,
        },
        mode: {
            type: String,
            default: 'create'
        },
        simulationInputs: {
            type: Object
        },
        simulationMetadataId: {
            type: Number
        },

        simulationId: {
            type: Number
        }

    },

    setup (props) {
        const store = useStore();

        const selectedMarker = computed(
            () => store.getters["map/selectedMarker"]
        );
        const selectedLink = computed(
            () => store.getters["map/currentLinks"]
        );

        const formTitle = computed(
            () => props.mode == 'update' ? 'Update Simulation' : 'Create Simulation'
        );


        const pushNewOptions = (item) => {
            return {label: item, value: Math.floor(Math.random() * 100)}
        }
        const currentStep = ref(1);
        const nextStepRequest = ref(false);
        const confirmingSimulationCreation = ref(false);
        const file = ref(null);

        const resolutions = [{key: 'low', value: 'Low'}, {key: 'medium_low', value: 'Medium Low'}, {
            key: 'medium_high',
            value: 'Medium High'
        }, {key: 'high', value: 'High'}]
        const marketProfiles = [{key: 'centralized', value: 'Centralized'}, {
            key: 'decentralized',
            value: 'Decentralized'
        }, {key: 'pool', value: 'Pool'}, {key: 'p2p', value: 'P2P'}, {key: 'community', value: 'Community'}]
        const dataProfiles = [{key: 'hourly', value: 'Hourly'}, {key: 'daily', value: 'Daily'}]
        const horizonBasisProfiles = [{key: 'weekly', value: 'Weekly'}, {
            key: 'monthly',
            value: 'Monthly'
        }, {key: 'years', value: 'Years'}]
        const productDiffProfiles = [{key: 'noPref', value: 'noPref'}, {
            key: 'co2Emissions',
            value: 'Co2 Emissions'
        }, {key: 'networkDistance', value: 'Network Distance'}]
        const binaryOptions = [{key: '1', value: 'Yes'}, {key: '0', value: 'No'}]
        const regions = COUNTRIES.map((country) => country.name)
        const timeslices = [{key: 'monthly', value: 'Monthly'}, {key: 'weekly', value: 'Weekly'}, {
            key: 'daily',
            value: 'Daily'
        },
            {key: 'quad-hourly', value: 'Quad-hourly'}, {key: 'bi-hourly', value: 'Bi-hourly'}, {
                key: 'hourly',
                value: 'Hourly'
            }]
        const emissions = ["co2"]


        const platformStorages = computed(() => {
            if (!form.extra.input_data.platform_storages) {
                return []
            }

            return form.extra.input_data.platform_storages.map((item) => item.storage) || []
        });

        const pushNewStorage = () => {
            let lastStorageIndex = platformStorages.value.length + 1
            form.extra.input_data.platform_storages.push(
                {
                    "storage": "Storage" + lastStorageIndex,
                    "capital_cost_storage": 750,
                    "dicount_rate_sto": 0.04,
                    "operational_life_sto": 100,
                    "storage_max_charge": 1500,
                    "storage_max_discharge": 1500,
                    "l2d": 0,
                    "tag_heating": 1,
                    "tag_cooling": 0,
                    "storage_return_temp": 50,
                    "storage_supply_temp": 80,
                    "storage_ambient_temp": 15,
                    "residual_storage_capacity": 0,
                    "max_storage_capacity": 1500,
                    "storage_level_start": 0,
                    "u_value": 0.21
                }
            )
        };
        const removeStorage = (index) => {
            form.extra.input_data.platform_storages.splice(index, 1)
        };

        const steps = computed(() => {

            let steps = [
                {
                    id: "Step 1",
                    name: "Sink & Sources",
                    status: mapStepStatus(1),
                },
            ]
            let fullSimulationStep = [
                {
                    id: "Step 2",
                    name: "GIS",
                    status: mapStepStatus(2),
                },
                {
                    id: "Step 3",
                    name: "TEO",
                    status: mapStepStatus(3),
                },
                {
                    id: "Step 4",
                    name: "Market",
                    status: mapStepStatus(4),
                },
                {
                    id: "Step 5",
                    name: "Business",
                    status: mapStepStatus(5),
                },
            ]

            if (form.simulation_metadata.data.identifier == 'demo_simulation') {

                if (form.simulation_metadata.data.hasOwnProperty('modules')) {
                    let modules = form.simulation_metadata.data.modules

                    return steps.concat(fullSimulationStep.filter((item) => modules.includes(item.name.toLowerCase())))
                } else {

                    steps = steps.concat(fullSimulationStep)
                }

            }

            return steps
        });

        const isFullSimulation = computed(() => form.simulation_metadata.data.identifier === 'demo_simulation');
        const isORCSimulation = computed(() => form.simulation_metadata.data.identifier === 'convert_orc');
        const isPinchAnalysisSimulation = computed(() => form.simulation_metadata.data.identifier === 'pinch_simulation');

        const totalSinkSourcesSelected = computed(() => form.extra.sinks.length + form.extra.sources.length)
        const technologyOwnershipOptions = computed(() => {

            let ownershipMap = []
            let technologyMap = []
            let nameMap = []
            form.extra.input_data.rls = []
            form.extra.input_data.rls_map = []
            let index = 1;
            for (let sourceIndex in form.extra.sources) {
                let sourceId = form.extra.sources[sourceIndex].id
                let sourceName = form.extra.sources[sourceIndex].name
                var ownership = 'source ' + sourceId
                var technology = `source ${sourceId} ext tech`
                ownershipMap.push(ownership)
                technologyMap.push(technology)
                nameMap.push(sourceName)
                form.extra.input_data.rls.push([ownership, technology])
                form.extra.input_data.rls_map.push([ownership, sourceName])
                index++
            }

            for (let sinkIndex in form.extra.sinks) {
                let sinkId = form.extra.sinks[sinkIndex].id
                let sinkName = form.extra.sinks[sinkIndex].name
                var ownership = 'sink ' + sinkId
                var technology = `sink ${sinkId} utl tech`
                ownershipMap.push(ownership)
                technologyMap.push(technology)
                nameMap.push(sinkName)
                form.extra.input_data.rls.push([ownership, technology])
                form.extra.input_data.rls_map.push([ownership, sinkName])
                index++
            }


            return {ownershipMap, technologyMap, nameMap}
        })

        const fillActorShare = () => {
            const sources = form.extra.sources || [];
            const sinks = form.extra.sinks || [];
            const totalSourcesAndSinks = sources.length + sinks.length;
            const actorShare = Array(totalSourcesAndSinks).fill(0);
            actorShare[0] = 1;
            form.extra.input_data.actorshare = JSON.stringify(actorShare);
        };


        const mapStepStatus = (index) => {
            if (currentStep.value === 5) {
                fillActorShare()
            }
            return currentStep.value === index
                ? "current"
                : currentStep.value < index
                    ? "upcoming"
                    : "complete";
        }

        const form = useForm({
            name: "Simulation Name",
            simulation_metadata: props.simulation_metadata[0],
            extra: {
                solver_teo: 'COPT',
                solver_gis: 'COPT',
                solver_market: 'COPT',
                file: null,
                isConstantUtil: true,
                input_data: {
                    pinch_analysis: {
                        interest_rate: 0.04,
                        pinch_delta_T_min: 20,
                        lifetime: 20,
                        number_output_options: 3,
                    },
                    get_best_number: 3,
                    orc_years_working: 25,
                    orc_T_evap: 110,
                    orc_T_cond: 35,
                    actorshare: null,
                    rls: [],
                    rls_map: [],
                    cost: null,
                    gmax: null,
                    lmax: null,
                    util: null,
                    fc_pip: 50,
                    vc_pip: 700,
                    chp_pars: null,
                    el_price: null,
                    agent_ids: null,
                    fc_dig_st: 350,
                    fc_dig_tr: 200,
                    flow_temp: 100,
                    objective: null,
                    prod_diff: null,
                    vc_dig_st: 700,
                    vc_dig_tr: 500,
                    vc_pip_ex: 1.3,
                    water_den: 1000,
                    offer_type: null,
                    recurrence: null,
                    block_offer: null,
                    ground_temp: 8,
                    nr_of_hours: 48,
                    return_temp: 70,
                    ambient_temp: 25,
                    data_profile: null,
                    el_dependent: null,
                    invest_pumps: 0,
                    vc_dig_st_ex: 1.1,
                    vc_dig_tr_ex: 1.3,
                    co2_emissions: null,
                    co2_intensity: 25.0,
                    discount_rate: [4, 5],
                    heat_capacity: 4.18,
                    horizon_basis: null,

                    // TEO BEGIN
                    platform_sets: {
                        "REGION": [
                            "Greece"
                        ],
                        "EMISSION": [
                            "co2"
                        ],
                        "emission_penalty": 15,
                        "TIMESLICE": "monthly",
                        "YEAR": [
                            2023
                        ],
                        "MODE_OF_OPERATION": [
                            1,
                            2
                        ],
                        "STORAGE": ["tankstorage"],
                        "platform_budget_limit": 150000000.0
                    },

                    // TEO END
                    "start_datetime": null,
                    "is_in_community": [],
                    "prod_diff_option": null,
                    "project_duration": 10,
                    "platform_storages": [
                        {
                            "storage": "tankstorage",
                            "capital_cost_storage": 750,
                            "dicount_rate_sto": 0.04,
                            "operational_life_sto": 100,
                            "storage_max_charge": 1500,
                            "storage_max_discharge": 1500,
                            "l2d": 0,
                            "tag_heating": 1,
                            "tag_cooling": 0,
                            "storage_return_temp": 50,
                            "storage_supply_temp": 80,
                            "storage_ambient_temp": 15,
                            "residual_storage_capacity": 0,
                            "max_storage_capacity": 1500,
                            "storage_level_start": 0,
                            "u_value": 0.21
                        },
                        {
                            "storage": "sourcestorage",
                            "capital_cost_storage": 450,
                            "dicount_rate_sto": 0.04,
                            "operational_life_sto": 25,
                            "storage_max_charge": 1000,
                            "storage_max_discharge": 1000,
                            "l2d": 0,
                            "tag_heating": 1,
                            "tag_cooling": 0,
                            "storage_return_temp": 50,
                            "storage_supply_temp": 80,
                            "storage_ambient_temp": 15,
                            "residual_storage_capacity": 0,
                            "max_storage_capacity": 1000,
                            "storage_level_start": 0,
                            "u_value": 0.22
                        }
                    ],
                    community_settings: null,
                    network_resolution: "high",
                    time_limit: 0,
                    yearly_demand_rate: 0.05,
                    factor_street_terrain: 0.1,
                    factor_street_overland: 0.4,
                    platform_annual_emission_limit: [
                        {
                            emission: "co2",
                            annual_emission_limit: 15000000000000
                        }
                    ],
                    ex_grid: [],
                    user: {
                        md: "centralized",
                        horizon_basis: "years",
                        recurrence: 1,
                        data_profile: "hourly",
                        yearly_demand_rate: 0.05,
                        start_datetime: "2023-01-01",
                        prod_diff_option: "noPref",
                        util: [0.7],
                        fbp_agent: 'None',
                        fbp_time: 'None',
                    }
                },
                links: [],
                sinks: [],
                sources: [],
                steps: 0,
            },
        });

        if (props.simulationInputs) {
            form.simulation_metadata = props.simulation_metadata.find((item) => item.id === props.simulationMetadataId)
            form.name = props.simulationInputs.name
            form.extra = JSON.parse(JSON.stringify(props.simulationInputs.extra));
            nextTick(() => {
                form.extra.input_data.actorshare = JSON.stringify(form.extra.input_data.actorshare)
                if (!form.extra.input_data.user) {
                    form.extra.input_data.user = {"util": []}
                }
            })

        }

        const completeActorShare = (data) => {
            let totalOfStreams = 0
            totalOfStreams = form.extra.sinks.reduce(
                (previous, current) => previous + current.values.streams_length,
                0
            )
            let list = JSON.parse(data)
            if (list) {
                for (var i = list.length; i < (totalOfStreams + form.extra.sources.length); i++) {
                    list[i] = 0
                }
            }

            return list
        }

        const convertListToNumeric = (input) => {
            for (let index in input) {
                input[index] = Number(input[index])
            }
            return input;
        }

        const onSubmit = () => {
            if (file && file.value) {
                form.extra.file = file.value.files[0]
            }

            form.transform((data) => {
                if (form.simulation_metadata.data.type !== 'standalone') {

                    data.extra.input_data.user.util = convertListToNumeric(data.extra.input_data.user.util)
                    data.extra.input_data.platform_sets.YEAR = convertListToNumeric(data.extra.input_data.platform_sets.YEAR)
                    data.extra.input_data.actorshare = JSON.parse(data.extra.input_data.actorshare)
                    form.extra.input_data.platform_annual_emission_limit[0].annual_emission_limit = Number(
                        form.extra.input_data.platform_annual_emission_limit[0].annual_emission_limit
                    )

                    // Numb GIS Parameter
                    data.extra.input_data.time_limit = Number(data.extra.input_data.time_limit)
                    data.extra.input_data.flow_temp = Number(data.extra.input_data.flow_temp)
                    data.extra.input_data.return_temp = Number(data.extra.input_data.return_temp)
                    data.extra.input_data.ambient_temp = Number(data.extra.input_data.ambient_temp)
                    data.extra.input_data.ground_temp = Number(data.extra.input_data.ground_temp)
                    data.extra.input_data.fc_dig_st = Number(data.extra.input_data.fc_dig_st)
                    data.extra.input_data.vc_dig_st = Number(data.extra.input_data.vc_dig_st)
                    data.extra.input_data.vc_dig_st_ex = Number(data.extra.input_data.vc_dig_st_ex)
                    data.extra.input_data.fc_pip = Number(data.extra.input_data.fc_pip)
                    data.extra.input_data.vc_pip = Number(data.extra.input_data.vc_pip)
                    data.extra.input_data.vc_pip_ex = Number(data.extra.input_data.vc_pip_ex)
                    data.extra.input_data.fc_dig_tr = Number(data.extra.input_data.fc_dig_tr)
                    data.extra.input_data.vc_dig_tr = Number(data.extra.input_data.vc_dig_tr)
                    data.extra.input_data.vc_dig_tr_ex = Number(data.extra.input_data.vc_dig_tr_ex)
                    data.extra.input_data.heat_capacity = Number(data.extra.input_data.heat_capacity)
                    data.extra.input_data.water_den = Number(data.extra.input_data.water_den)
                    data.extra.input_data.factor_street_terrain = Number(data.extra.input_data.factor_street_terrain)
                    data.extra.input_data.factor_street_overland = Number(data.extra.input_data.factor_street_overland)
                    data.extra.input_data.invest_pumps = Number(data.extra.input_data.invest_pumps)


                    //IF the user select to use a constant value the we should repeat the util value value for each stream
                    if (form.extra.isConstantUtil) {

                        let totalOfStreams = 0
                        let constantValue = data.extra.input_data.user.util[0]
                        try {
                            totalOfStreams = form.extra.sinks.reduce(
                                (previous, current) => previous + current.values.streams_length,
                                0
                            )

                            for (let i = 0; i < totalOfStreams; i++) {
                                data.extra.input_data.user.util[i] = Number(constantValue)
                            }
                        } catch (e) {
                            console.log(e)
                        }


                    }

                    //Select all the storage's created
                    data.extra.input_data.platform_sets.STORAGE = platformStorages.value
                }

                return data
            })

            if (props.mode === 'update') {
                form.patch(route('projects.simulations.update', {
                    project: props.project.id,
                    simulation: props.simulationId
                }), {
                    onError: (errors) => {
                        confirmingSimulationCreation.value = false
                    }
                });

            } else {
                form.post(route('projects.simulations.store', {id: props.project.id}), {
                    onError: (errors) => {
                        confirmingSimulationCreation.value = false
                    }
                });
            }


        };


        const onCancel = () => {
            form.extra.links.forEach((link) => onDeselected(link));
            form.extra.links = [];
            form.extra.sinks = [];
            form.extra.sources = [];
        };

        const selectAllSinks = () => {
            form.extra.sinks = sinks.value
        }
        const selectAllSources = () => {
            form.extra.sources = sources.value
        }
        const selectAllLinks = () => {
            form.extra.links = links.value
        }

        const project_poly = polygon(props.project.data.polygon);
        const project_instances = props.instances.filter((r) =>
            project_poly
                .getBounds()
                .contains(marker(r.location.data.center).getLatLng())
        );

        const sinks = computed(() => {
            if (!project_instances || isORCSimulation.value) return [];
            return project_instances.filter((i) => i.template.category.type == "sink");
        });

        const sources = computed(() => {
            if (!project_instances) return [];
            return project_instances.filter((i) => i.template.category.type == "source");
        });

        const links = computed(() => {
            return props.links;
        });

        const stepInfo = computed(() => {
            return [];
            // if (!form.simulation_metadata) return [];
            // const steps = Object.keys(form.simulation_metadata.data).filter(
            //     (a) => !["start", "type"].includes(a)
            // );

            // return steps.map((s) => ({
            //     step: s,
            //     module: form.simulation_metadata.data[s].module.name,
            //     function: form.simulation_metadata.data[s].function,
            // }));
        });

        const onSelected = (value) => {
            value.forEach(element => {
                if (!selectedLink.value[element.id]) {
                    store.dispatch("map/setLink", {
                        id: element.id,
                        link: element,
                    });
                }
            });
        };

        const solvers = [
            {name: 'SCIP', id: 'SCIP'},
            {name: 'GUROBI', id: 'GUROBI'},
            {name: 'HIGHS', id: 'HIGHS'},
            {name: 'COPT', id: 'COPT'}
        ]

        const onDeselected = (value) => {
            store.dispatch("map/unsetLink", value.id);
        };
        watch(
            isORCSimulation,
            () => {
                if (isORCSimulation.value) {
                    form.extra.sinks = [];
                }
            },
            {immediate: true, deep: true}
        );

        watch(
            form.extra,
            (instances, oldInstances) => {
                if (oldInstances) {
                    mapUtils.setMarker(instances);

                    store.dispatch("map/selectMarker", {
                        marker: null,
                        type: null,
                    });
                }
            },
            {immediate: true, deep: true}
        );

        watch(
            selectedMarker,
            () => {
                const type = store.getters["map/selectedMarkerType"];
                let index = -1;
                switch (type) {
                    case 'sources':
                        index = form.extra.sources.findIndex((e) => JSON.stringify(e.location.data.center) == JSON.stringify(selectedMarker.value));
                        if (index != -1) {
                            form.extra.sources.splice(index, 1)
                        } else {
                            let elementSource = sources.value.find((e) => JSON.stringify(e.location.data.center) == JSON.stringify(selectedMarker.value));
                            if (elementSource) {
                                form.extra.sources.push(elementSource);
                            } else {
                                store.commit("objects/setNotify", {
                                    title: 'Source',
                                    text: 'Marker outside the simulation area',
                                    type: 'warning'
                                });
                            }
                        }
                        break;
                    case 'sinks':
                        index = form.extra.sinks.findIndex((e) => JSON.stringify(e.location.data.center) == JSON.stringify(selectedMarker.value));
                        if (index != -1) {
                            form.extra.sinks.splice(index, 1);
                        } else {
                            let elementSink = sinks.value.find((e) => JSON.stringify(e.location.data.center) == JSON.stringify(selectedMarker.value));
                            if (elementSink) {
                                form.extra.sinks.push(elementSink);
                            } else {
                                store.commit("objects/setNotify", {
                                    title: 'Sink',
                                    text: isORCSimulation.value ? 'Locked marker in ORC simulation' : 'Marker outside the simulation area',
                                    type: 'warning'
                                });
                            }
                        }
                        break;
                    case 'links':
                        index = form.extra.links.findIndex((e) => e.id == selectedMarker.value.id);
                        if (index != -1) {
                            store.dispatch("map/unsetLink", selectedMarker.value.id);
                            form.extra.links.splice(index, 1)
                        } else {
                            store.dispatch("map/setLink", {
                                id: selectedMarker.value.id,
                                link: selectedMarker.value,
                            });
                            form.extra.links.push(links.value.find((e) => e.id == selectedMarker.value.id));
                        }
                        break;
                }
            },
            {immediate: true, deep: true}
        );

        const isGreaterThanOneYear = computed(() => {

            let result = false
            switch (form.extra.input_data.user.horizon_basis) {
                case "weekly":
                    result = (form.extra.input_data.user.recurrence > 52)
                    break
                case "monthly":
                    result = (form.extra.input_data.user.recurrence > 12)
                    break
                case "years":
                    result = (form.extra.input_data.user.recurrence > 1)
                    break
            }
            console.log(form.extra.input_data.user.horizon_basis, result)
            return result
        })

        return {
            form,
            links,
            sinks,
            sources,
            steps,
            currentStep,
            resolutions,
            marketProfiles,
            dataProfiles,
            horizonBasisProfiles,
            productDiffProfiles,
            regions,
            emissions,
            platformStorages,
            confirmingSimulationCreation,
            isFullSimulation,
            isPinchAnalysisSimulation,
            binaryOptions,
            formTitle,
            timeslices,
            totalSinkSourcesSelected,
            file,
            technologyOwnershipOptions,
            isGreaterThanOneYear,
            onDeselected,
            onSelected,
            onSubmit,
            onCancel,
            selectAllLinks,
            selectAllSinks,
            selectAllSources,
            pushNewStorage,
            removeStorage,
            convertListToNumeric,
            pushNewOptions,
            completeActorShare,
            solvers


        };
    },
}
</script>
