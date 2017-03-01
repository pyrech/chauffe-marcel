import React, { Component } from 'react';
import {
    ActivityIndicator,
    Alert,
    AsyncStorage,
    Button,
    ScrollView,
    StyleSheet,
    Text,
    Vibration,
    View,
} from 'react-native';
import { STORE_KEYS } from '../constants/storage.js';
import ConfigurationEdit from './ConfigurationEdit.js';
import TimeSlotEdit from './TimeSlotEdit.js';
import ModeBar from './ModeBar.js';
import TimeTable from './TimeTable.js';
import Modal from './Modal.js';
import { checkStatus, toJson } from '../utils/api';
import { MODES } from '../constants/mode.js';
import sdk from 'chauffe-marcel-sdk';

export default class App extends Component {
    constructor(props) {
        super(props);

        this.state = {
            host: '',
            apiKey: '',
            mode: null,
            timeSlots: {},
            timeSlotEdited: null,
            timeSlotEditVisible: false,
            configurationEditVisible: false,
            displayIndicator: true,
        };

        AsyncStorage.multiGet(
            [STORE_KEYS.host, STORE_KEYS.apiKey],
            (err, stores) => {
                this.setState({
                    host: stores[0][1],
                    apiKey: stores[1][1],
                });

                if (this.state.host && this.state.apiKey) {
                    this.client = this.createClient(this.state.host, this.state.apiKey);
                    this.fetchMode();
                    this.fetchTimeSlots();
                }
            }
        );

        this.fetchMode = this.fetchMode.bind(this);
        this.fetchTimeSlots = this.fetchTimeSlots.bind(this);
        this.fetchTimeSlot = this.fetchTimeSlot.bind(this);
        this.vibrateOnButtonPress = this.vibrateOnButtonPress.bind(this);
        this.onChangeMode = this.onChangeMode.bind(this);
        this.onRefresh = this.onRefresh.bind(this);
        this.onSaveTimeSlot = this.onSaveTimeSlot.bind(this);
        this.onRemoveTimeSlot = this.onRemoveTimeSlot.bind(this);
        this.onTestConfiguration = this.onTestConfiguration.bind(this);
        this.onSaveConfiguration = this.onSaveConfiguration.bind(this);
    }

    createClient(host, apiKey) {
        return sdk({
            endpoint: host,
            securityHandlers: {
                'Bearer': (headers, params, schemePart) => {
                    headers.Authorization = apiKey;

                    return true;
                }
            },
        });
    }

    fetchMode() {
        if (this.client) {
            const mode = this.client.getMode();

            mode
                .then(checkStatus)
                .then(toJson)
                .then(data => {
                    this.setState({
                        mode: data,
                    });
                })
                .catch(err => {
                    Alert.alert(
                        'Problem detected',
                        err.message,
                        [
                            {text: 'OK'},
                        ]
                    )
                })
            ;
        }
    }

    fetchTimeSlots(handleError = true) {
        if (this.client) {
            const timeSlots = this.client.getTimeSlots();

            timeSlots
                .then(checkStatus)
                .then(toJson)
                .then(data => {
                    const timeSlotsByUuid = {};
                    data.map(timeSlot => timeSlotsByUuid[timeSlot.uuid] = timeSlot);
                    this.setState({
                        timeSlots: timeSlotsByUuid,
                    });
                })
                .catch(err => {
                    if (handleError) {
                        Alert.alert(
                            'Problem detected',
                            err.message,
                            [
                                {text: 'OK'},
                            ]
                        )
                    }
                })
            ;
        }
    }

    fetchTimeSlot(uuid) {
        if (this.client) {
            const timeSlot = this.client.getTimeSlot({uuid});

            timeSlot
                .then(checkStatus)
                .then(toJson)
                .then(data => {
                    const timeSlotsByUuid = Object.assign({}, this.state.timeSlots);
                    timeSlotsByUuid[uuid] = data;
                    this.setState({
                        timeSlots: timeSlotsByUuid,
                    });
                })
                .catch(error => {
                    if (handleError) {
                        Alert.alert(
                            'Problem detected',
                            err.message,
                            [
                                {text: 'OK'},
                            ]
                        )
                    }
                })
            ;
        }
    }

    vibrateOnButtonPress() {
        Vibration.vibrate([0, 50]);
    }

    render() {
        return (
            <ScrollView style={styles.container}>
                <ModeBar
                    activeMode={this.state.mode}
                    onModeChange={this.onChangeMode}
                />
                <View style={styles.refreshButtons}>
                    <Button onPress={this.onRefresh} title="Refresh" color="#0384FF" accessibilityLabel="Refresh data from the server (mode, time slots)" />
                </View>
                <TimeTable
                    enabled={this.state.mode === MODES.NOT_FORCED}
                    dayHeight={320}
                    timeSlots={this.state.timeSlots}
                    onTimeSlotPress={(timeSlot) => {
                        this.vibrateOnButtonPress();
                        this.setState({
                            timeSlotEdited: timeSlot,
                            timeSlotEditVisible: true,
                        })
                    }}
                />
                <View style={styles.footerButtons}>
                    <Button onPress={() => {
                        this.vibrateOnButtonPress();
                        this.setState({timeSlotEdited: null, timeSlotEditVisible: true})
                    }} title="Create time slot" color="#55D062" accessibilityLabel="Add a new time slot to the weekly program" />
                    <Button onPress={() => {
                        this.vibrateOnButtonPress();
                        this.setState({configurationEditVisible: true})
                    }} title="Configure server" color="#0384FF" accessibilityLabel="Configure server's domain and api key" />
                </View>
                <Modal
                    title={this.state.timeSlotEdited ? 'Editing time slot' : 'Creating time slot'}
                    visible={this.state.timeSlotEditVisible}
                    onRequestClose={() => this.setState({timeSlotEditVisible: false})}
                >
                    <TimeSlotEdit
                        timeSlot={this.state.timeSlotEdited}
                        onSave={this.onSaveTimeSlot}
                        onRemove={this.onRemoveTimeSlot}
                    />
                </Modal>
                <Modal
                    title="Configuring server"
                    visible={this.state.configurationEditVisible}
                    onRequestClose={() => this.setState({configurationEditVisible: false})}
                >
                    <ConfigurationEdit
                        host={this.state.host}
                        apiKey={this.state.apiKey}
                        onTest={this.onTestConfiguration}
                        onSave={this.onSaveConfiguration}
                    />
                </Modal>
                <ActivityIndicator
                    animating={this.state.displayIndicator}
                    style={[styles.centering, {height: 80}]}
                    size="large"
                />
            </ScrollView>
        );
    }

    onChangeMode(mode) {
        this.vibrateOnButtonPress();
        this.client.updateMode({
            body: mode,
        })
            .then(checkStatus)
            .then(toJson)
            .then(data => {
                if (data) {
                    this.fetchMode();
                }
            })
            .catch(err => {
                Alert.alert(
                    'Error!',
                    'An error occured when changing the mode (' + err.message + ').',
                    [
                        {text: 'OK'},
                    ]
                )
            })
        ;
    }

    onRefresh() {
        this.vibrateOnButtonPress();
        this.fetchMode();
        this.fetchTimeSlots(false);
    }

    onSaveTimeSlot(timeSlot) {
        this.vibrateOnButtonPress();
        if (timeSlot.uuid) {
            this.client.updateTimeSlot({
                uuid: timeSlot.uuid,
                body: timeSlot,
            })
                .then(checkStatus)
                .then(toJson)
                .then(data => {
                    this.setState({
                        timeSlotEditVisible: false,
                    });
                    this.fetchTimeSlot(timeSlot.uuid);
                })
                .catch(err => {
                    Alert.alert(
                        'Error!',
                        'An error occured when saving time slot (' + err.message + ').',
                        [
                            {text: 'OK'},
                        ]
                    )
                })
            ;
        } else {
            this.client.createTimeSlot({
                body: timeSlot,
            })
                .then(checkStatus)
                .then(toJson)
                .then(data => {
                    this.setState({
                        timeSlotEditVisible: false,
                    });
                    this.fetchTimeSlot(data);
                })
                .catch(err => {
                    Alert.alert(
                        'Error!',
                        'An error occured when saving time slot (' + err.message + ').',
                        [
                            {text: 'OK'},
                        ]
                    )
                })
            ;
        }
    }

    onRemoveTimeSlot(timeSlot) {
        this.vibrateOnButtonPress();
        if (!timeSlot.uuid) {
            return;
        }

        this.client.deleteTimeSlot({
            uuid: timeSlot.uuid,
        })
            .then(checkStatus)
            .then(toJson)
            .then(data => {
                if (data) {
                    this.setState({
                        timeSlotEditVisible: false,
                    });
                    this.fetchTimeSlots();
                }
            })
            .catch(err => {
                Alert.alert(
                    'Error!',
                    'An error occured when removing time slot (' + err.message + ').',
                    [
                        {text: 'OK'},
                    ]
                )
            })
        ;
    }

    onTestConfiguration(host, apiKey) {
        this.vibrateOnButtonPress();
        const testClient = this.createClient(host, apiKey);
        const mode = testClient.getMode();

        mode
            .then(checkStatus)
            .then(toJson)
            .then(data => {
                Alert.alert(
                    'Good job!',
                    'Configuration looks good, the server responded successfully.',
                    [
                        {text: 'OK'},
                    ]
                )
            })
            .catch((err) => {
                Alert.alert(
                    'Problem detected',
                    err.message,
                    [
                        {text: 'OK'},
                    ]
                )
            })
        ;
    }

    onSaveConfiguration(host, apiKey) {
        this.vibrateOnButtonPress();
        AsyncStorage
            .multiSet(
                [
                    [STORE_KEYS.host, host],
                    [STORE_KEYS.apiKey, apiKey],
                ],
                (errors) => {
                    if (errors) {
                        Alert.alert(
                            'Error!',
                            'An error occured when saving configuration (' + errors.join() + ').',
                            [
                                {text: 'OK'},
                            ]
                        );
                        return;
                    }
                    this.setState({
                        host,
                        apiKey,
                        configurationEditVisible: false,
                    });
                    this.client = this.createClient(this.state.host, this.state.apiKey);
                    this.fetchMode();
                    this.fetchTimeSlots();
                }
            )
        ;
    }
}

const styles = StyleSheet.create({
    container: {
        flex: 1,
        backgroundColor: '#FFFFFF',
    },
    refreshButtons: {
        marginTop: 20,
        marginBottom: 20,
        flexDirection: 'row',
        justifyContent: 'space-around',
        alignItems: 'center',
    },
    footerButtons: {
        marginTop: 20,
        marginBottom: 20,
        flexDirection: 'row',
        justifyContent: 'space-around',
        alignItems: 'center',
    },
});
