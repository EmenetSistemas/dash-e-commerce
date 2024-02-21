import { ComponentFixture, TestBed } from '@angular/core/testing';

import { ConsultaPlanesComponent } from './consulta-planes.component';

describe('ConsultaPlanesComponent', () => {
  let component: ConsultaPlanesComponent;
  let fixture: ComponentFixture<ConsultaPlanesComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [ConsultaPlanesComponent]
    });
    fixture = TestBed.createComponent(ConsultaPlanesComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
