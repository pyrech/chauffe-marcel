import React, { PropTypes, Component } from 'react';
import {
    Image,
    StyleSheet,
    Text,
    TouchableNativeFeedback,
    View,
} from 'react-native';
import { DAYS } from '../constants/day.js';

export default class TimeTable extends Component {
    constructor(props) {
        super(props);

        this.dispatchTimeSlotsByDay = this.dispatchTimeSlotsByDay.bind(this);
        this.renderDay = this.renderDay.bind(this);
        this.renderTimeSlot = this.renderTimeSlot.bind(this);
        this.calculatePosition = this.calculatePosition.bind(this);

        this.dispatchTimeSlotsByDay(props);
    }

    componentWillReceiveProps(props) {
        this.dispatchTimeSlotsByDay(props);
    }

    dispatchTimeSlotsByDay(props) {
        let timeSlotsByDay = {};

        for (uuid of Object.keys(props.timeSlots)) {
            const timeSlot = props.timeSlots[uuid];
            timeSlotsByDay[timeSlot.dayOfWeek] = timeSlotsByDay[timeSlot.dayOfWeek] || [];
            timeSlotsByDay[timeSlot.dayOfWeek].push(timeSlot);
        }

        this.timeSlotsByDay = timeSlotsByDay;
    }

    renderDay(day) {
        day = parseInt(day);

        const timeSlots = this.timeSlotsByDay && this.timeSlotsByDay[day] || [];
        const style = {
            height: this.props.dayHeight
        };
        const now = new Date();
        const currentDay = (now.getDay()) || 7;

        let nowLine = null;

        if (!this.props.enabled) {
            style.backgroundColor = 'gray';
        }

        if (currentDay === day) {
            style.backgroundColor = 'gray';
            nowLine = <View
                key={'nowLine'}
                style={[
                    styles.nowLine,
                    {
                        top: this.calculatePosition(now.getHours() + ':' + now.getMinutes()),
                    },
                ]}
            />;
        }

        return (
            <View
                key={'day-' + day}
                style={[styles.day, style]}
            >
                {timeSlots.map(this.renderTimeSlot)}
                {nowLine}
            </View>
        );
    }

    renderTimeSlot(timeSlot) {
        const top = this.calculatePosition(timeSlot.start);
        const height = this.calculatePosition(timeSlot.end) - top;

        return (
            <TouchableNativeFeedback
                key={'timeSlot-' + timeSlot.uuid}
                onPress={() => this.props.onTimeSlotPress(timeSlot)}
            >
                <View style={[styles.timeSlot, {top, height}]}>
                    <Text>{timeSlot.start + ' - ' + timeSlot.end}</Text>
                </View>
            </TouchableNativeFeedback>
        );
    }

    render() {
        return (
            <View style={styles.container}>
                {Object.keys(DAYS).map(this.renderDay)}
            </View>
        );
    }

    calculatePosition(time) {
        const [hour, minute] = time.split(':');

        return parseInt(((parseInt(hour) + parseInt(minute)/60) / 24) * this.props.dayHeight);
    }
}

TimeTable.propTypes = {
    enabled: PropTypes.bool.isRequired,
    timeSlots: PropTypes.object.isRequired,
    dayHeight: PropTypes.number.isRequired,
    onTimeSlotPress: PropTypes.func.isRequired,
};

const styles = StyleSheet.create({
    container: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        paddingLeft: 2,
        paddingRight: 2,
    },
    day: {
        position: 'relative',
        flex: 0.12,
        marginLeft: 2,
        marginRight: 2,
        borderColor: 'black',
        borderWidth: 1,
    },
    timeSlot: {
        position: 'absolute',
        backgroundColor: 'green',
    },
    nowLine: {
        position: 'absolute',
        height: 1,
        left: 0,
        right: 0,
        backgroundColor: 'red',
    }
});
