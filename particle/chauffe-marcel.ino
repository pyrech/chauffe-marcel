// Configuration of the pins
const int controlPin = D0;
const int redLedPin = D1;
const int greenLedPin = D2;

// The current status of the heating controller
bool status = false;

void setup()
{
    pinMode(controlPin, OUTPUT);
    pinMode(redLedPin, OUTPUT);
    pinMode(greenLedPin, OUTPUT);

    Particle.function("enable", enable);
    Particle.function("disable", disable);
    Particle.variable("status", &status, BOOLEAN);

    update();
}

int enable(String data)
{
    status = true;
    update();
}

int disable(String data)
{
    status = false;
    update();
}

void update()
{
    digitalWrite(controlPin, status ? HIGH : LOW);
    digitalWrite(redLedPin, status ? LOW : HIGH);
    digitalWrite(greenLedPin, status ? HIGH : LOW);
}
