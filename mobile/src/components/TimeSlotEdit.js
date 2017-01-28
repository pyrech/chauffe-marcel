import React, { Component, PropTypes } from 'react';
import {
    Button,
    Picker,
    StyleSheet,
    Text,
    TextInput,
    View,
} from 'react-native';
import { DAYS } from '../constants/day.js';

export default class TimeSlotEdit extends Component {
    constructor(props) {
        super(props);

        this.state = {
            timeSlot: Object.assign({}, this.props.timeSlot) || {},
        }
    }

    renderTextRow(label, value, onChange) {
        return (
            <View style={styles.rowContainer}>
                <View style={styles.label}>
                    <Text>{label}</Text>
                </View>
                <View>
                    <TextInput
                        style={styles.input}
                        onChangeText={onChange}
                        value={value}
                    />
                </View>
            </View>
        );
    }

    renderPickerRow(label, value, choices, onChange) {
        const items = [];

        for (choice of Object.keys(choices)) {
            items.push(
                <Picker.Item
                    key={choice}
                    label={choices[choice]}
                    value={'' + choice}
                />
            );
        }

        return (
            <View style={styles.rowContainer}>
                <View style={styles.label}>
                    <Text>{label}</Text>
                </View>
                <View>
                    <Picker
                        selectedValue={'' + value}
                        onValueChange={onChange}
                    >
                        {items}
                    </Picker>
                </View>
            </View>
        );
    }

    render() {
        return (
            <View style={styles.container}>
                {this.renderTextRow('Start', this.state.timeSlot.start, (value) => {
                    const timeSlot = Object.assign({}, this.state.timeSlot);
                    timeSlot.start = value;
                    this.setState({timeSlot});
                })}
                {this.renderTextRow('End', this.state.timeSlot.end, (value) => {
                    const timeSlot = Object.assign({}, this.state.timeSlot);
                    timeSlot.end = value;
                    this.setState({timeSlot});
                })}
                {this.renderPickerRow('Day of week', this.state.timeSlot.dayOfWeek, DAYS, (value) => {
                    const timeSlot = Object.assign({}, this.state.timeSlot);
                    timeSlot.dayOfWeek = parseInt(value);
                    this.setState({timeSlot});
                })}
                <View style={styles.footerButtons}>
                    <Button onPress={() => this.props.onSave(this.state.timeSlot)} title="Save" color="green" accessibilityLabel="Save the time slot" />
                    { this.state.timeSlot.uuid && <Button onPress={() => this.props.onRemove(this.state.timeSlot)} title="Remove" color="red" accessibilityLabel="Remove the time slot" /> }
                </View>
            </View>
        );
    }
}

TimeSlotEdit.propTypes = {
    timeSlot: PropTypes.object,
    onSave: PropTypes.func.isRequired,
};

const styles = StyleSheet.create({
    rowContainer: {
        flexDirection: 'column',
        marginVertical: 2,
        flex: 1,
    },
    label: {
        width: 115,
        alignItems: 'flex-end',
        marginRight: 10,
        paddingTop: 2,
    },
});
