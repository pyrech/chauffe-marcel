import React, { PropTypes, Component } from 'react';
import {
    Image,
    Text,
    TouchableNativeFeedback,
    View,
    StyleSheet,
} from 'react-native';
import { MODES } from '../constants/mode.js';

export default class ModeBar extends Component {
    constructor(props) {
        super(props);

        this.renderModeButton = this.renderModeButton.bind(this);
    }

    renderModeButton(mode) {
        let icon;
        // The image name in require has to be known statically
        switch (mode) {
            case MODES.FORCED_ON:
                icon = require('../assets/mode/forced_on.png');
                break;
            case MODES.FORCED_OFF:
                icon = require('../assets/mode/forced_off.png');
                break;
            case MODES.NOT_FORCED:
                icon = require('../assets/mode/not_forced.png');
                break;
        }
        return (
            <TouchableNativeFeedback
                style={styles.touchable}
                key={'mode_' + mode}
                onPress={() => this.props.onModeChange(mode)}
                background={TouchableNativeFeedback.SelectableBackground()}
            >
                <View
                    style={styles.button}
                >
                    <Image
                        style={styles.image}
                        source={icon}
                    />
                    {
                        mode === this.props.activeMode
                        ? <View style={styles.active}></View>
                        : null
                    }
                </View>
            </TouchableNativeFeedback>
        );
    }

    render() {
        const buttons = Object.keys(MODES).map(key => this.renderModeButton(MODES[key]));

        return (
            <View style={styles.container}>
                <Text style={styles.title}>Chauffe Marcel</Text>
                <View style={styles.modes}>
                    {buttons}
                </View>
            </View>
        );
    }
}

ModeBar.propTypes = {
    activeMode: PropTypes.string,
    onModeChange: PropTypes.func.isRequired,
};

const styles = StyleSheet.create({
    container: {
        flexDirection: 'row',
        justifyContent: 'space-between',
        alignItems: 'center',
        paddingTop: 10,
        paddingLeft: 30,
        paddingBottom: 10,
        backgroundColor: '#1D232A',
    },
    title: {
        color: 'white',
        fontSize: 18,
        fontWeight: 'bold',
    },
    modes: {
        flexDirection: 'row',
        justifyContent: 'flex-end',
    },
    touchable: {
        borderRadius: 20,
    },
    button: {
        marginRight: 10,
    },
    image: {
        width: 40,
        height: 40,
    },
    active: {
        backgroundColor: '#000000',
        opacity: 0.6,
        width: 40,
        height: 40,
        position: 'absolute',
        zIndex: 1,
        right:0,
        top:0,
        borderRadius: 20,
    },
});
