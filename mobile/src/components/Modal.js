import React, { PropTypes, Component } from 'react';
import {
    Modal as ReactModal,
    ScrollView,
    StyleSheet,
    Text,
    TouchableNativeFeedback,
    View
} from 'react-native';

export default class Modal extends Component {
    render() {
        return (
            <ReactModal
                {...this.props}
                animationType="slide"
                transparent={true}
                supportedOrientations={["portrait"]}
                onOrientationChange={() => {}}
            >
                <View style={styles.modal}>
                    <TouchableNativeFeedback style={styles.shadow} onPress={this.props.onRequestClose}>
                        <View></View>
                    </TouchableNativeFeedback>
                    <ScrollView style={styles.container}>
                        {this.props.title ?
                            <Text style={styles.title}>{this.props.title}</Text>
                        : null}
                        {this.props.children}
                    </ScrollView>
                </View>
            </ReactModal>
        );
    }
}

Modal.propTypes = {
    title: PropTypes.string,
    visible: PropTypes.bool.isRequired,
    onRequestClose: PropTypes.func.isRequired,
};

const styles = StyleSheet.create({
    modal: {
        position: 'relative',
        flex: 1,
        justifyContent: 'center',
        alignItems: 'center',
    },
    shadow: {
        flex: 1,
        backgroundColor: 'rgba(0, 0, 0, 1)',
    },
    container: {
        maxHeight: 450,
        backgroundColor: 'white',
        padding: 20,
    },
    title: {
        padding: 20,
        color: 'black',
        fontWeight: 'bold',
        fontSize: 20,
    },
});
