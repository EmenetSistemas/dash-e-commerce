import { ComponentFixture, TestBed } from '@angular/core/testing';

import { CatalogoCategoriasComponent } from './catalogo-categorias.component';

describe('CatalogoCategoriasComponent', () => {
  let component: CatalogoCategoriasComponent;
  let fixture: ComponentFixture<CatalogoCategoriasComponent>;

  beforeEach(() => {
    TestBed.configureTestingModule({
      declarations: [CatalogoCategoriasComponent]
    });
    fixture = TestBed.createComponent(CatalogoCategoriasComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
