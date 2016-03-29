package co.watly.server.rest.events;

import java.util.Date;

import com.fasterxml.jackson.annotation.JsonSetter;

public class Event {
	private Float Certainty;
	private Float Cost;
	private String Name;
	private String EventSource;
	private String Annotation;
	private String EventId;
	private Date DetectionTime;
	private Float duration;
	
	public Float getCertainty() {
		return Certainty;
	}
	@JsonSetter("Certainty")
	public void setCertainty(Float certainty) {
		Certainty = certainty;
	}
	
	public Float getCost() {
		return Cost;
	}
	@JsonSetter("Cost")
	public void setCost(Float cost) {
		Cost = cost;
	}
	
	public String getName() {
		return Name;
	}
	@JsonSetter("Name")
	public void setName(String name) {
		Name = name;
	}
	public String getEventSource() {
		return EventSource;
	}
	@JsonSetter("EventSource")
	public void setEventSource(String eventSource) {
		EventSource = eventSource;
	}
	
	public String getAnnotation() {
		return Annotation;
	}
	@JsonSetter("Annotation")
	public void setAnnotation(String annotation) {
		Annotation = annotation;
	}
	
	public String getEventId() {
		return EventId;
	}
	@JsonSetter("EventId")
	public void setEventId(String eventId) {
		EventId = eventId;
	}
	
	public Date getDetectionTime() {
		return DetectionTime;
	}
	@JsonSetter("DetectionTime")
	public void setDetectionTime(Date detectionTime) {
		DetectionTime = detectionTime;
	}
	
	public Float getDuration() {
		return duration;
	}
	@JsonSetter("Duration")
	public void setDuration(Float duration) {
		this.duration = duration;
	}

}

//{"Certainty":"0.0","Cost":"0.0","Name":"TrafficReport","EventSource":"","Annotation":"","Duration":"0.0",
//"volume":"4000","EventId":"744d6a23-3feb-411c-936a-1bf53ea5e95f","DetectionTime":"18\/02\/2016-15:06:03"}

//{"Certainty":"0.0","Cost":"10.0","Name":"ExpectedCrash","EventSource":"","OccurrenceTime":"19\/02\/2016-10:41:44",
//"Annotation":"","Duration":"0.0","EventId":"4a36e056-408d-439f-87c8-ff720b6cce38","DetectionTime":"19\/02\/2016-10:41:44"}